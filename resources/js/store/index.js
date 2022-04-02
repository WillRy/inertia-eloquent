import {createStore} from "vuex";

export default createStore({
    state: {
        alunos_reload: {},
        alunos_id_edicao: null,
        alunos_id_exclusao: null
    },
    actions: {

    },
    mutations: {
        SET_ALUNOS_RELOAD(state, obj) {
            state.alunos_reload = obj;
        },
        SET_ALUNOS_ID_EDICAO(state, id) {
            state.alunos_id_edicao = id;
        },
        SET_ALUNOS_ID_EXCLUSAO(state, id) {
            state.alunos_id_exclusao = id;
        }
    }
})
