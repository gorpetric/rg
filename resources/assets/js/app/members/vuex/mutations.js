export const setData = (state, data) => {
    state.members.active = data.members.active
    state.members.inactive = data.members.inactive

    state.membership_monthly = data.meta.membership_monthly
    state.membership_daily = data.meta.membership_daily

    state.currencies.all = data.meta.currencies.all
    state.currencies.default = data.meta.currencies.default
}

export const toggleLoading = (state) => {
    state.loading = !state.loading
}

export const addNewMember = (state, newMember) => {
    newMember.payments = []
    if(!newMember.active) {
        state.members.inactive.unshift(newMember)
        return
    }

    state.members.active.unshift(newMember)
}

export const editMember = (state, data) => {
    let activeWord = data.activeState ? 'active' : 'inactive'

    let index = state.members[activeWord].findIndex((member) => member.id == data.member.id)
    state.members[activeWord].splice(index, 1)

    let newActiveWord = data.member.active ? 'active' : 'inactive'
    state.members[newActiveWord].unshift(data.member)
}
