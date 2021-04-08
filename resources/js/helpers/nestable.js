
/**
 * Initialize Nestable plugin
 */
function initializeNestable(element, maxDepth, onChangedCallbackFunction) {
    // initialize select2
    element.nestable({
        maxDepth: maxDepth
    }).on('change', onChangedCallbackFunction);
}

function buildCategoriesList(categoriesListData) {
    // define node path
    let path = [];
    // define categories list
    let categoriesList = [];
    _.forEach(categoriesListData, function(item, index) {
        if (item.categoryParentId === 0) {
            // add key to current item
            _.set(item, 'children', []);
            // push current item to categories list
            categoriesList.push(item);
            // add current item id to node path
            path.push(item.categoryId);
        } else {
            // add item to current children list
            categoriesList[_.indexOf(path, item.categoryParentId)].children.push(item);
        }
    });

    return categoriesList;
}


/**
 * Export
 */
export {
    initializeNestable,
    buildCategoriesList
}
