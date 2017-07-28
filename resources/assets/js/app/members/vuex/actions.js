export const getData = ({ commit, dispatch }) => {
    return axios.get('clanovi/podaci').then(response => {
        commit('setData', response.data.data)
        commit('toggleLoading')
    })
}
