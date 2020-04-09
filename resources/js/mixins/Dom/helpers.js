const domHelpers = {
    methods: {
        getChildElementsByClassName(parentElement, aClassName) {
            if(parentElement) {
                let result = [];
                let children = parentElement.children;
                for (let i = 0; i < children.length; i++) {
                    let child = children[i];
                    if (child.classList.contains(aClassName)) {
                        result.push(child);
                    }
                    result = result.concat(
                        this.getChildElementsByClassName(child, aClassName)
                    );
                }

                return result;
            } else {
                return [];
            }
        }
    }
};

export default domHelpers;
