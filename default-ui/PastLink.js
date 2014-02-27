var PastLink = (function(document, window, rangy) {
    var Construct = function(incompleteData) {
        incompleteData = incompleteData || {
            author: '',
            authorInstitution: '',
            authorProfession: ''
        };

        incompleteData.href = document.location;
        incompleteData.text = rangy.getSelection().text();
        incompleteData.hash = md5(
            rangy.superSanitize(
                incompleteData.author +
                incompleteData.authorInstitution +
                incompleteData.authorProfession
            )
            ,
            rangy.superSanitize(incompleteData.text)
        );

        incompleteData.href = incompleteData.href.toString();

        this.data = incompleteData;
    };

    Construct.prototype = {
        toString: function() {
            return JSON.stringify(this.data);
        },
        toClipBoardData: function() {
            return encodeURIComponent(this.toString());
        }
    };

    return Construct;
})(document, window, rangy);