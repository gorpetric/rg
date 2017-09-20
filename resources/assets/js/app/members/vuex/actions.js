export const getData = ({ commit, dispatch }) => {
    return axios.get('members/data').then(response => {
        commit('setData', response.data.data)
        commit('toggleLoading')
    })
}

export const addNewMember = ({commit, dispatch}, newMember) => {
    commit('addNewMember', newMember)
}

export const editMemberData = ({commit, dispatch}, data) => {
    commit('editMember', data)
}
