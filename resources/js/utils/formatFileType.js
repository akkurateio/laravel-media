const formatFileType = value =>
    value
        .split('/')
        .pop()
        .toUpperCase();
export default formatFileType;
