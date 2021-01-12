import { FILES, GRID } from '../../constant';
import resourceService from '../../services/resources.service';

const media = {
    namespaced: true,
    state: {
        items: [],
        uploading: false,
        loading: false,
        loadingSearch: false,
        tabSelected: FILES,
        modeSelected: GRID,
        imageSelected: null,
        search: '',
        meta: {
            current_page: 0,
            from: 0,
            last_page: '',
            per_page: 0,
            to: 0,
            total: 0,
        },
        showInfo: false,
    },
    mutations: {
        setShowInfo(state, value) {
            state.showInfo = value;
        },
        setMeta(state, value) {
            state.meta = value;
        },
        setSearch(state, value) {
            state.search = value;
        },
        setMode(state, value) {
            state.modeSelected = value;
        },
        setTab(state, value) {
            state.tabSelected = value;
        },
        setLoading(state, value) {
            state.loading = value;
        },
        setLoadingSearch(state, value) {
            state.setLoadingSearch = value;
        },
        setItems(state, value) {
            state.items = value;
        },
        updateItem(state, { id, name, alt, legend }) {
            state.items = state.items.map(item => {
                if (item.id === id) {
                    return {
                        ...item,
                        name,
                        legend,
                        alt,
                    };
                }
                return item;
            });
        },
        deleteItem(state, value) {
            state.items = state.items.filter(item => item.id !== value);
        },
        updateTags(state, { id, tags }) {
            state.items = state.items.map(item => {
                if (item.id === id) {
                    const newItem = {
                        ...item,
                        tags,
                    };
                    if (state.imageSelected && state.imageSelected.id === id) {
                        state.imageSelected = newItem;
                    }
                    return newItem;
                }
                return item;
            });
        },
        setImageSelected(state, value) {
            if (!value) {
                state.showInfo = false;
            }
            state.imageSelected = value;
        },
        setUploading(state, value) {
            state.uploading = value;
        },
    },
    actions: {
        async fetchNextItem({ commit, state }) {
            commit('setLoading', true);
            const { data } = await axios.get(
                `${origin}/api/v1/accounts/${
                    location.pathname.split('/')[2]
                }/packages/media/resources`,
                {
                    params: {
                        include: 'media,tags,user',
                        'page[number]': state.meta.current_page + 1,
                        'filter[search]': state.search,
                    },
                }
            );
            commit('setMeta', data.meta);
            commit('setItems', [...state.items, ...data.data.items]);
            commit('setLoading', false);
        },
        async fetchItems({ commit, state }) {
            commit('setLoading', true);
            const { data } = await resourceService.getAll({
                include: 'media,tags,user',
                'filter[search]': state.search,
            });
            commit('setMeta', data.meta);
            commit('setItems', data.data.items);
            commit('setLoading', false);
        },
        async updateItem({ commit }, { id, form }) {
            commit('setLoading', true);
            await axios.patch(
                `${origin}/api/v1/accounts/${
                    location.pathname.split('/')[2]
                }/packages/media/medias/${id}`,
                form
            );
            const { name, alt, legend } = form;
            commit('updateItem', { id, name, alt, legend });
            commit('setLoading', false);
        },
        async deleteItem({ commit, dispatch }, id) {
            commit('setLoading', true);
            await axios.delete(
                `${origin}/api/v1/accounts/${
                    location.pathname.split('/')[2]
                }/packages/media/medias/${id}`
            );
            dispatch('fetchItems');
            commit('setLoading', false);
        },
        selectTab({ commit }, value) {
            commit('setTab', value);
        },
        async searchAction({ commit, dispatch }, value) {
            commit('setSearch', value);
            commit('setLoadingSearch', true);
            await dispatch('fetchItems');
            commit('setLoadingSearch', false);
        },
        async toggleInfo({ commit, state }) {
            if (!state.imageSelected) {
                return;
            }
            commit('setShowInfo', !state.showInfo);
        },
    },
    getters: {
        hasNextPage: state => state.meta.current_page < state.meta.last_page,
    },
};
export default media;
