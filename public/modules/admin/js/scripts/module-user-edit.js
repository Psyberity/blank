$(document).ready(function(){
    $("#form").validate({
        rules: {
            user_id: {
                required: true
            }
        }
    });
});

var obj = $('#user_id');
$(obj).select2({
    initSelection : function (element, callback) {
        callback(element.val());
    },
    ajax: {
        type: 'POST',
        url: '/ajax/api/',
        quietMillis: 100,
        data: function (term) {
            return {controller: apiController, action: apiAction, search: term};
        },
        results: function (data) {
            return {results: data.items, more: false};
        }
    },
    formatResult: searchResult,
    formatSelection: searchSelection,
    dropdownCssClass: "bigdrop",
    allowClear: true,
    escapeMarkup: function (m) {
        return m;
    }
});
$(obj).select2('data', user_id);

function searchResult(item) {
    var markup = "<table class='sel2-result'><tr>";
    markup += "<td class='sel2-info'>" + item.selection;
    markup += "</td></tr></table>";
    return markup;
}

function searchSelection(item) {
    return item.selection;
}