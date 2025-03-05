<?php

namespace Aspro\Smartseo\Admin\Controllers;

use \Aspro\Smartseo,
    \Aspro\Smartseo\Admin\App\Controller,
    \Bitrix\Main\Web\Json,
    \Bitrix\Main\Localization\Loc;

class SeoTextController extends Controller
{

    public function getViewFolderName()
    {
        return 'seo_text';
    }

    public function actionList($section_id = null)
    {
        $order = [
            'by' => 'ID',
            'order' => 'DESC',
        ];

        if ($this->request->get('by') && $this->request->get('order')) {
            $order = [
                'by' => $this->request->get('by'),
                'order' => $this->request->get('order'),
            ];
        }

        $ui = new Smartseo\Admin\UI\SeoTextAdminUI();

        $adminUiList = new \CAdminUiList($ui->getGridId(), new \CAdminUiSorting($ui->getGridId(), $order['by'], $order['order']));

        $adminUiList->AddAdminContextMenu($ui->getContextMenu(), $isShowExcel = false);

        $this->listenEditActionAdminUiList($adminUiList);

        $this->listenGroupActionAdminUiList($adminUiList);

        $adminUiList->AddHeaders($ui->getGridColumns());

        $filter = [];

        $adminUiList->AddFilter($ui->getFilterFields(), $filter);

        $rsData = new \CAdminUiResult(
          $this->getRsData([
              $order['by'] => $order['order']
            ], $filter), $ui->getGridId()
        );

        $rsData->NavStart();

        $adminUiList->SetNavigationParams($rsData);

        $this->render('list', [
            'adminUiList' => $adminUiList,
            'filterFields' => $ui->getFilterFields(),
            'rsData' => $rsData,
        ]);
    }

    public function actionDelete()
    {
        if (!$this->validateAjaxAction()) {
            echo Json::encode([
                'result' => false,
                'message' => $this->getErrors(),
            ]);
        }

        $id = $this->request->get('id');

        if (!$this->delete($id)) {
            throw new \Exception(implode('<br>', $this->getErrors()));
        }

        echo Json::encode([
            'result' => true,
            'message' => 'Seo text deleted successfully'
        ]);
    }

    public function actionCopy()
    {
        if (!$this->validateAjaxAction()) {
            echo Json::encode([
                'result' => false,
                'message' => $this->getErrors(),
            ]);
        }

        $id = $this->request->get('id');

        if (!$this->copy($id)) {
            throw new \Exception(implode('<br>', $this->getErrors()));
        }

        echo Json::encode([
            'result' => true,
            'message' => 'Seo text copy successfully'
        ]);
    }

    private function getRsData($order = [], $filter = [])
    {
        $elements = $this->getElements($order, $filter);

        $result = new \CDBResult;

        $result->InitFromArray($elements);

        return $result;
    }

    private function getElements($order = [], $filter = [])
    {
        foreach ($filter as $field => $value) {
            if (preg_match('[IBLOCK_\d+_SECTION_ID]', $field)) {
                $filter['IBLOCK_SECTIONS.SECTION_ID'][] = $value;
                unset($filter[$field]);
            }
        }

        if ($filter['NAME']) {
            $filter['NAME'] = '%' . $filter['NAME'] . '%';
        }

        $rsRows = Smartseo\Models\SmartseoSeoTextTable::getList([
              'select' => [
                  '*',
                  'REF_IBLOCK_' => 'IBLOCK'
              ],
              'order' => $order,
              'filter' => $filter,
              'cache' => [
                  'ttl' => Smartseo\Models\SmartseoSeoTextTable::getCacheTtl(),
              ]
        ]);

        $elements = [];
        $elementIds = [];
        while ($_row = $rsRows->fetch()) {
            $elementIds[] = $_row['ID'];
            $elements['E' . $_row['ID']] = $_row;
            $elements['E' . $_row['ID']]['ROW_TYPE'] = 'E';
        }

        $iblockSectionRows = $this->getIblockSectionsByElementIds($elementIds);

        foreach ($iblockSectionRows as $elementId => $iblockSections) {
            $elements['E' . $elementId]['IBLOCK_SECTIONS'] = $iblockSections;
        }

        return $elements;
    }

    private function getIblockSectionsByElementIds($ids)
    {
        $rows = Smartseo\Models\SmartseoSeoTextIblockSectionsTable::getList([
              'select' => [
                  'SEO_TEXT_ID',
                  'REF_SECTION_ID' => 'SECTION.ID',
                  'REF_SECTION_NAME' => 'SECTION.NAME',
                  'REF_DEPTH_LEVEL' => 'SECTION.DEPTH_LEVEL',
                  'REF_LEFT_MARGIN' => 'SECTION.LEFT_MARGIN'
              ],
              'filter' => [
                  'SEO_TEXT_ID' => $ids
              ],
              'order' => [
                  'REF_LEFT_MARGIN' => 'ASC',
                  'REF_DEPTH_LEVEL' => 'ASC',
                  'REF_SECTION_NAME' => 'ASC'],
              'cache' => [
                  'ttl' => Smartseo\Models\SmartseoSeoTextIblockSectionsTable::getCacheTtl(),
              ],
          ])
          ->fetchAll();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['SEO_TEXT_ID']][] = [
                'ID' => $row['REF_SECTION_ID'],
                'NAME' => $row['REF_SECTION_NAME'],
                'DEPTH_LEVEL' => $row['REF_DEPTH_LEVEL'],
            ];
        }

        return $result;
    }

    private function listenGroupActionAdminUiList(\CAdminUiList &$adminUiList)
    {
        if (!$action = $this->request->get('action_button_' . $adminUiList->table_id)) {
            return;
        }

        if (!$mixidResultIds = $adminUiList->GroupAction()) {
            return;
        }

        $elementIds = [];
        $sectionIds = [];

        foreach ($mixidResultIds as $mixidId) {
            $_type = substr($mixidId, 0, 1);
            $_id = (int) substr($mixidId, 1);

            switch ($_type) {
                case 'E' :
                    $elementIds[] = $_id;
                    break;
            }
        }

        $elementCollection = null;
        if ($elementIds) {
            $elementCollection = Smartseo\Models\SmartseoSeoTextTable::getList([
                  'filter' => [
                      'ID' => $elementIds
                  ]
              ])->fetchCollection();
        }

        switch ($action) {
            case 'delete' :
                $this->deleteElementByIds($elementIds);
                break;
        }

        if ($this->hasErrors()) {
            foreach ($this->getErrors() as $errorMessage) {
                $adminUiList->AddGroupError($errorMessage);
            }
        }
    }

    private function listenEditActionAdminUiList(\CAdminUiList &$adminUiList)
    {
         if ($adminUiList->EditAction()) {
            $mixidResults = $this->request->get('FIELDS');

            foreach ($mixidResults as $mixidId => $mixidData) {
                if (!$adminUiList->IsUpdated($mixidId)) {
                    continue;
                }

                $_type = substr($mixidId, 0, 1);
                $_id = (int) substr($mixidId, 1);

                switch ($_type) {
                    case 'E' :
                        Smartseo\Models\SmartseoSeoTextTable::update($_id, array_filter($mixidData, function($code) {
                              return Smartseo\Models\SmartseoSeoTextTable::hasMapField($code);
                          }, ARRAY_FILTER_USE_KEY));
                        break;
                }
            }
        }
    }

    private function deleteElementByIds($elementIds)
    {
        global $DB;

        if (!$elementIds) {
            return;
        }

        try {
            $DB->StartTransaction();

            foreach ($elementIds as $elementId) {
                $result = Smartseo\Models\SmartseoSeoTextTable::delete($elementId);

                if (!$result->isSuccess()) {
                    $this->setErrors($result->getErrorMessages());
                }
            }

            $DB->Commit();
        } catch (Exception $e) {
            $DB->Rollback();
        }
    }

    private function validateAjaxAction()
    {
        if (!$this->request->isAjaxRequest()) {
            throw new \Exception(Loc::getMessage('SMARTSEO_INDEX__ERROR__NOT_AJAX'));
        }

        if (!check_bitrix_sessid()) {
            $this->addError('Bitrix session not found');

            return false;
        }

        if (!$this->request->get('id')) {
            $this->addError('ID param expected');

            return false;
        }

        return true;
    }

    private function delete($id)
    {
        if (!Smartseo\Models\SmartseoSeoTextTable::getByPrimary($id)) {
            $this->addError('Seo text not found');

            return false;
        }

        $result = Smartseo\Models\SmartseoSeoTextTable::delete($id);

        if (!$result->isSuccess()) {
            $this->setErrors($result->getErrorMessages());

            return false;
        }

        return true;
    }

    private function copy($id)
    {
        if (!Smartseo\Models\SmartseoSeoTextTable::getByPrimary($id)) {
            $this->addError('Seo text not found');

            return false;
        }

        $replication = new Smartseo\Admin\Actions\Replication();

        global $DB;

        try {
            $DB->StartTransaction();

            if (!$replication->copySeotext($id) || $replication->hasErrors()) {
                throw new \Exception(implode('<br/>', $replication->getErrors()));
            }

            $DB->Commit();
        } catch (Exception $e) {
            $DB->Rollback();
        }

        return true;
    }

}
