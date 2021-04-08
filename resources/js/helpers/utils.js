/**
 * Convert object to query string
 * @param inputObject
 * @returns {string}
 */
export function convertObjectToQueryString(inputObject) {
    return Object.keys(inputObject).map(key =>
        key + '=' + inputObject[key]
    ).join('&')
}
