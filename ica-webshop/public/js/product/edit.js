init();

function init() {
    setEventListenerToAddStock();
    setEventListenerToRemoveStock();
    setEventListenerToAddImage();
    setEventListenerToRemoveImage();
    setEventListenerForStoredProductImagesDiv();

    syncButtonColorsWithCheckboxes();
}

function setEventListenerToAddStock() {
    document.getElementById('addStock').addEventListener('click', addStock);
}

function setEventListenerToRemoveStock() {
    document.getElementById('stock-fields').addEventListener('click', removeStock);
}

function setEventListenerToAddImage() {
    document.getElementById('addImage').addEventListener('click', addImage);
}

function setEventListenerToRemoveImage() {
    document.getElementById('image-fields').addEventListener('click', removeImage);
}

function addToStockFieldsCounter(number) {
    setStockFieldsCounterValue(getStockFieldsCounterValue() - (-number));
}

function addToImageFieldsCounter(number) {
    setImageFieldsCounterValue(getImageFieldsCounterValue() - (-number));
}

function addStock(event) {
    event.preventDefault();
    addStockField();
    addToStockFieldsCounter(1);
}

function removeStock(event) {
    if(event.target && event.target.id.startsWith('removeStockButton')) {
        event.preventDefault();
        removeStockFieldById(event.target.id);
        reorganizeStockFieldsIndexes();
    }
}

function addImage(event) {
    event.preventDefault();
    addImageField();
    addToImageFieldsCounter(1);
}

function removeImage(event) {
    if(event.target && event.target.id.startsWith('removeImageButton')) {
        event.preventDefault();
        removeImageFieldById(event.target.id);
        reorganizeImageFieldsIndexes();
    }
}

function removeStockFieldById(elementId) {
    document.getElementById(elementId).parentElement.parentElement.remove();
}

function removeImageFieldById(elementId) {
    document.getElementById(elementId).parentElement.parentElement.remove();
}

function reorganizeFieldsIndexes() {
    reorganizeStockFieldsIndexes();
    reorganizeImageFieldsIndexes();
}

function reorganizeStockFieldsIndexes() {
    const stockFieldDivs = document.getElementsByClassName('stock-field');
    let index = 0;

    for(const stockFieldDiv of stockFieldDivs) {
        const stockFieldInputs = stockFieldDiv.getElementsByClassName('stock-fields-input');
        const removeButton = stockFieldDiv.getElementsByClassName('removeStock')[0];
        changeStockFieldsIndexes(stockFieldInputs[0], stockFieldInputs[1], stockFieldInputs[2], removeButton, index);
        ++index;
    };

    setStockFieldsCounterValue(index);
}

function reorganizeImageFieldsIndexes() {
    const imageFieldDivs = document.getElementsByClassName('image-field');
    let index = 0;

    for(const imageFieldDiv of imageFieldDivs) {
        const imageFieldInput = imageFieldDiv.getElementsByClassName('image-fields-input')[0];
        const removeButton = imageFieldDiv.getElementsByClassName('removeImage')[0];
        changeImageFieldsIndexes(imageFieldInput, removeButton, index);
        ++index;
    };

    setImageFieldsCounterValue(index);
}

function changeStockFieldsIndexes(colorField, sizeField, inStockField, removeButton, index) {
    changeFieldIndex(colorField, 'stock_color_', index);
    changeFieldIndex(sizeField, 'stock_size_', index);
    changeFieldIndex(inStockField, 'stock_in_stock_', index);
    removeButton.id = 'removeStockButton_' + index;
}

function changeImageFieldsIndexes(imageField, removeButton, index) {
    changeFieldIndex(imageField, 'image_', index);
    removeButton.id = 'removeImageButton_' + index;
}

function setStockFieldsCounterValue(value) {
    document.getElementById('stock_fields_counter').value = value;
}

function getStockFieldsCounterValue() {
    return document.getElementById('stock_fields_counter').value;
}

function setImageFieldsCounterValue(value) {
    document.getElementById('image_fields_counter').value = value;
}

function getImageFieldsCounterValue() {
    return document.getElementById('image_fields_counter').value;
}

function changeFieldIndex(field, str, index) {
    for(classItem of field.classList) {
        if(classItem.startsWith(str)) {
            field.classList.remove(classItem);
        }
    };
    field.classList.add(str + index);
    field.name = str + index;
}

function addStockField() {
    let stockFieldsDiv = document.getElementById('stock-fields');
    let newStockField = createNewStockField();
    stockFieldsDiv.appendChild(newStockField);
}

function addImageField() {
    let imageFieldsDiv = document.getElementById('image-fields');
    let newImageField = createNewImageField();
    imageFieldsDiv.appendChild(newImageField);
}

function createNewStockField() {
    let newStockField = document.createElement("div");
    newStockField.classList.add("row", "mt-2", "stock-field");

    for(inputGroup of createNewStockInputGroup()) {
        newStockField.appendChild(inputGroup);
    };

    return newStockField;
}

function createNewImageField() {
    let newImageField = document.createElement("div");
    newImageField.classList.add("row", "mt-2", "image-field");

    for(inputGroup of createNewImageInputGroup()) {
        newImageField.appendChild(inputGroup);
    };

    return newImageField;
}

function createNewStockInputGroup() {
    let newButtonDiv = createNewDiv();
    newButtonDiv.appendChild(createNewStockRemoveButtonField());
    return [
        createNewTextInputDiv("stock_size_" + getNextRemoveStockId()),
        createNewTextInputDiv("stock_color_" + getNextRemoveStockId()), 
        createNewTextInputDiv("stock_in_stock_" + getNextRemoveStockId()), 
        newButtonDiv
    ];
}

function createNewImageInputGroup() {
    let newButtonDiv = createNewDiv("col-2");
    newButtonDiv.appendChild(createNewImageRemoveButtonField());
    return [
        createNewFileInputDiv("image_" + getNextRemoveImageId()),
        newButtonDiv
    ];
}

function createNewTextInputDiv(name) {
    let div = createNewDiv();
    div.appendChild(createNewTextInputField(name));
    return div;
}

function createNewFileInputDiv(name) {
    let div = createNewDiv();
    div.appendChild(createNewFileInputField(name));
    return div;
}

function createNewDiv(classItem = "col") {
    let div = document.createElement("div");
    div.classList.add(classItem);
    return div;
}

function createNewTextInputField(name) {
    let newTextInputField = document.createElement("input");
    newTextInputField.type = "text";
    newTextInputField.name = name;
    newTextInputField.classList.add("stock-fields-input");
    newTextInputField.classList.add("form-control");
    return newTextInputField;
}

function createNewStockRemoveButtonField() {
    let buttonField = document.createElement('button');
    buttonField.classList.add("btn", "btn-secondary", "px-3", "removeStock");
    buttonField.id = "removeStockButton_" + getNextRemoveStockId();
    buttonField.innerHTML = "X";
    return buttonField;
}

function createNewFileInputField(name) {
    let newFileInputField = document.createElement("input");
    newFileInputField.type = "file";
    newFileInputField.name = name;
    newFileInputField.classList.add("image-fields-input");
    newFileInputField.classList.add("form-control");
    return newFileInputField;
}

function createNewImageRemoveButtonField() {
    let buttonField = document.createElement('button');
    buttonField.classList.add("btn", "btn-secondary", "px-3", "removeImage");
    buttonField.id = "removeImageButton_" + getNextRemoveImageId();
    buttonField.innerHTML = "X";
    return buttonField;
}

function getNextRemoveStockId() {
    const removeStockButtons = document.getElementsByClassName("removeStock");
    return removeStockButtons.length;
}

function getNextRemoveImageId() {
    const removeImageButtons = document.getElementsByClassName("removeImage");
    return removeImageButtons.length;
}

function setEventListenerForStoredProductImagesDiv() {
    getStoredProductImagesDiv().addEventListener('click', function(event) {
        if(event.target) {
            if(event.target.id.startsWith('removeStoredProductImageCheckButton')) {
                event.preventDefault();
                productImageId = event.target.id.substr(36)
                clickCheckboxById('removeStoredProductImageCheckBox_' + productImageId);
            } else if(event.target.id.startsWith('removeStoredProductImageCheckBox')) {
                productImageId = event.target.id.substr(33)
                toggleButtonColorById('removeStoredProductImageCheckButton_' + productImageId, ['secondary', 'dark']);
                toggleImageById('storedProductImage_' + productImageId)
            }
        }
    });
}

function getStoredImageCheckButtons() {
    getStoredProductImagesDiv().getElementsByClassName('removeStoredProductImageCheckButton');
}

function getStoredProductImagesDiv() {
    return document.getElementById('stored-product-images');
}

function clickCheckboxById(checkBoxId) {
    document.getElementById(checkBoxId).click();
}

function toggleButtonColorById(buttonId, classElems) {
    const button = document.getElementById(buttonId);
    for (const elem of classElems) {
        button.classList.toggle('btn-' + elem)
    }
}

function toggleImageById(imageId) {
    const image = document.getElementById(imageId);
    image.style['filter'] = image.style['filter'] == 'brightness(30%)' ? '' : 'brightness(30%)';
}

function syncButtonColorsWithCheckboxes() {
    const removeProductImagesButtons = document.getElementsByClassName('removeStoredProductImageCheckButton');    

    for (const removeProductImageButton of removeProductImagesButtons) {
        const productImageId = removeProductImageButton.id.substr(36);
        if(document.getElementById('removeStoredProductImageCheckBox_' + productImageId).checked) {
            toggleButtonColorById('removeStoredProductImageCheckButton_' + productImageId, ['secondary', 'dark']);
            toggleImageById('storedProductImage_' + productImageId)
        }
    }
}