export const setData = (state, data) => {
    state.members.active = data.members.active
    state.members.inactive = data.members.inactive

    state.membership_monthly = data.meta.membership_monthly
    state.membership_daily = data.meta.membership_daily
}

export const toggleLoading = (state) => {
    state.loading = !state.loading
}
