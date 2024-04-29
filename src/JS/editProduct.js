function showProduct(productId) {
    var form = document.createElement("form");
    form.setAttribute("method", "get");
    form.setAttribute("action", "edit.php");
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "producto_id");
    hiddenField.setAttribute("value", productId);
    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
}
