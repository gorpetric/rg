export const getData = ({ commit, dispatch }) => {
    return axios.get('members/data').then(response => {
        commit('setData', response.data.data)
        commit('toggleLoading')
    })
}
