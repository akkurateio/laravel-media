const formatSize = value => {
    const first = Math.round(value / 1000);
    if (first < 1000) {
        return new Intl.NumberFormat('fr-FR').format(first) + ' Ko';
    }
    return (
        new Intl.NumberFormat('fr-FR', { maximumSignificantDigits: 2 }).format(
            first / 1000
        ) + ' Mo'
    );
};
export default formatSize;
