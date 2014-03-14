var flp = {
    pastLinks: {},
    futureLinks: {},
    addPastLink: function(link) {
        var pair = link.settings.pairs[0];
        if (!this.pastLinks[pair.futureText.sanitized]) {
            this.pastLinks[pair.futureText.sanitized] = [];
        }
        this.pastLinks[pair.futureText.sanitized].push(link);
    },
    addFutureLink: function(link) {
        var pair = link.settings.pairs[0];
        if (!this.pastLinks[pair.pastText.sanitized]) {
            this.pastLinks[pair.pastText.sanitized] = [];
        }
        this.pastLinks[pair.pastText.sanitized].push(link);
    },
    selectAndScrollToPastLink: function(sanitized) {
        var links, link;
        if ((links = this.pastLinks[sanitized]) && (link = links[0])) {
            link.selectAndScrollTo();
            return true;
        }
        return false;
    },
    selectAndScrollToFutureLink: function(sanitized) {
        var links, link;
        if ((links = this.pastLinks[sanitized]) && (link = links[0])) {
            link.selectAndScrollTo();
            return true;
        }
        return false;
    }
};