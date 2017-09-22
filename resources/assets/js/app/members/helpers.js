import moment from 'moment'

const getLatestValidUntil = (member) => {
    if(!member.payments.length) return moment(member.joined_at).startOf('day')
    return moment(member.payments[0].valid_until).startOf('day')
}

const getDaysDifference = (member) => {
    let date = getLatestValidUntil(member)
    return date.diff(moment().startOf('day'), 'days')
}

export { getLatestValidUntil, getDaysDifference }
