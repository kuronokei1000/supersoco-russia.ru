var AppMpMapStoreSetting = BX.BitrixVue.createApp({
    data() {
        return {
            config: {
                ajaxUrl: '/bitrix/tools/aspro.lite/marketplace/wb_detail.php',
                sessid: '',
            },
            state: {
                map: {},
                stores: [],
                storeSelectOptions: [],
                wbStoreSelectOptions: []
            },
        }
    },
    computed: {
        ajaxUrl() {
            return this.config.ajaxUrl;
        },
        sessid() {
            return this.config.sessid || '';
        },
        wbStoreSelectOptions() {
            return this.state.wbStoreSelectOptions;
        }
    },
    methods: {
        setConfig(payload) {
            if (typeof payload === 'object') {
                this.config = Object.assign({}, this.config, payload);
            }
        },
        loadData(payload) {
            this.$set(this.state, 'stores', payload.stores || []);
            this.$set(this.state, 'storeSelectOptions', payload.storeSelectOptions || []);
            this.$set(this.state, 'wbStoreSelectOptions', payload.wbStoreSelectOptions || []);

            if (payload.map && typeof payload.map === 'object' && Object.keys(payload.map).length) {
                this.$set(this.state, 'map', payload.map);
            }
        },
        updateMapStore(bxStoreId, wbStoreId) {
            if (!this.state.map[bxStoreId]) {
                this.$set(this.state.map, bxStoreId, {
                    'BX_STORE_ID': bxStoreId,
                    'WB_STORE_ID': '',
                });
            }

            this.state.map[bxStoreId]['WB_STORE_ID'] = wbStoreId;
        },
    },
});
