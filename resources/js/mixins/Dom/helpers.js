const domHelpers = {
    methods: {
        getChildElementsByClassName(parentElement, aClassName) {
            let result = [];
            let children = parentElement.children;
            for(let i = 0; i < children.length; i++) {
                let child = children[i];
                if(child.classList.contains(aClassName)) {
                    result.push(child);
                }
                result = result.concat(
                    this.getChildElementsByClassName(child, aClassName)
                );
            }

            return result;
        }
    }
};

export default domHelpers;
