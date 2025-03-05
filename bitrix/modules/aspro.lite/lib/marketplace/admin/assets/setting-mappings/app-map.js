var AppMpMapSetting = BX.BitrixVue.createApp({
    data() {
        return {
            config: {
                ajaxUrl: '/bitrix/tools/aspro.lite/marketplace/wb_detail.php',
                ajaxUrlPropValuesMatch: '',
                controller: 'wildberries',
                sessid: '',
                iblockId: null,
                apiKey: null,
                clientId: null,
                externalSections: null,
                useSkuProps: false,
            },
            state: {
                map: {},
                sections: {},
                selectedSectionIds: [],
                propertySelectOptions: {},
                propertySkuSelectOptions: {},
            },
        }
    },
    mounted() {

    },
    computed: {
        ajaxUrl() {
            return this.config.ajaxUrl;
        },
        ajaxUrlPropValuesMatch() {
            return this.config.ajaxUrlPropValuesMatch;
        },
        controller() {
            return this.config.controller;
        },
        sessid() {
            return this.config.sessid || '';
        },
        iblockId() {
            return this.config.iblockId || '';
        },
        apiKey() {
            return this.config.apiKey || '';
        },
        clientId() {
            return this.config.clientId || '';
        },
        externalSections() {
            return this.config.externalSections || '';
        },
        useSkuProps() {
            return this.config.useSkuProps || false;
        },
        propertySelectOptions() {
            return this.state.propertySelectOptions;
        },
        propertySkuSelectOptions() {
            return this.state.propertySkuSelectOptions;
        },
    },
    methods: {
        setConfig(payload) {
            if (typeof payload === 'object') {
                this.config = Object.assign({}, this.config, payload);
            }
        },
        loadData(payload) {
            this.$set(this.state, 'sections', payload.sections || {});
            this.$set(this.state, 'propertySelectOptions', payload.propertySelectOptions || {});
            this.$set(this.state, 'propertySkuSelectOptions', payload.propertySkuSelectOptions || {});

            if (payload.selectedSectionIds && payload.selectedSectionIds.length) {
                this.$set(this.state, 'selectedSectionIds', payload.selectedSectionIds);
            }

            if (payload.map && typeof payload.map === 'object' && Object.keys(payload.map).length) {
                this.$set(this.state, 'map', payload.map);
            }
        },

        updateMapSection(bxSectionId, wbSectionCode) {
            if (!this.state.map[bxSectionId]) {
                this.$set(this.state.map, bxSectionId, {
                    'BX_SECTION': bxSectionId,
                    'WB_SECTION': '',
                    'PROPERTIES': [],
                });
            }

            this.state.map[bxSectionId]['WB_SECTION'] = wbSectionCode;
        },

        updateMapProperty(bxSectionId, bxPropertyCode, wbPropertyCode, wbDefaultValue, bxSkuPropertyCode) {
            if (
                !this.state.map[bxSectionId]
                || !this.state.map[bxSectionId]['WB_SECTION']
            ) {
                return;
            }

            let properties = this.state.map[bxSectionId]['PROPERTIES'];

            const findIndex = properties.findIndex(item => item.WB_PROPERTY === wbPropertyCode);

            let property = {
                'BX_PROPERTY': bxPropertyCode || '',
                'BX_SKU_PROPERTY': bxSkuPropertyCode || '',
                'WB_PROPERTY': wbPropertyCode,
                'DEFAULT_VALUE': wbDefaultValue || '',
            };

            if (findIndex < 0) {
                properties.push(property);
            } else {
                properties.splice(findIndex, 1, property)
            }
        },

        deleteMapSection(bxSectionId) {
            if (!this.state.map[bxSectionId]) {
                return;
            }

            this.$delete(this.state.map, bxSectionId);
        }
    },
});
