const getAll = (params = {}) =>
    axios.get(
        `${origin}/api/v1/accounts/${
            location.pathname.split('/')[2]
        }/packages/media/resources`,
        { params }
    );
const getById = (id) =>
    axios.get(
        `${origin}/api/v1/accounts/${
            location.pathname.split('/')[2]
        }/packages/media/resources/${id}`
    );
const resourceService = {
    getAll,
    getById,
};
export default resourceService;
