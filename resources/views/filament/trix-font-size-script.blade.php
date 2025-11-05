<style>
    .font-size-dropdown {
        display: inline-block;
        margin-right: 0.5rem;
    }
    .font-size-dropdown select {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid rgb(209 213 219);
        font-size: 0.875rem;
        background-color: white;
        cursor: pointer;
    }
    .font-size-dropdown select:focus {
        outline: none;
        border-color: rgb(99 102 241);
    }
    .dark .font-size-dropdown select {
        background-color: rgb(31 41 55);
        border-color: rgb(75 85 99);
        color: white;
    }
</style>

<script>
// Configure Trix before it initializes
document.addEventListener('trix-before-initialize', function() {
    if (window.Trix && !Trix.config.textAttributes.fontSize12) {
        Trix.config.textAttributes.fontSize12 = { 
            tagName: "span", 
            style: { fontSize: "12px" },
            inheritable: true
        };
        Trix.config.textAttributes.fontSize16 = { 
            tagName: "span", 
            style: { fontSize: "16px" },
            inheritable: true
        };
        Trix.config.textAttributes.fontSize20 = { 
            tagName: "span", 
            style: { fontSize: "20px" },
            inheritable: true
        };
        Trix.config.textAttributes.fontSize24 = { 
            tagName: "span", 
            style: { fontSize: "24px" },
            inheritable: true
        };
        Trix.config.textAttributes.fontSize32 = { 
            tagName: "span", 
            style: { fontSize: "32px" },
            inheritable: true
        };
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('trix-initialize', function(event) {
        const editor = event.target;
        const toolbar = editor.previousElementSibling;
        
        if (!toolbar || toolbar.classList.contains('trix-font-size-added')) {
            return;
        }
        
        toolbar.classList.add('trix-font-size-added');
        
        const fontSizeGroup = document.createElement('span');
        fontSizeGroup.className = 'font-size-dropdown';
        fontSizeGroup.setAttribute('data-trix-button-group', 'font-tools');
        
        const select = document.createElement('select');
        select.title = 'Font Size';
        
        const sizes = [
            { label: 'Normal', value: '' },
            { label: 'Small (12px)', value: 'fontSize12' },
            { label: 'Medium (16px)', value: 'fontSize16' },
            { label: 'Large (20px)', value: 'fontSize20' },
            { label: 'X-Large (24px)', value: 'fontSize24' },
            { label: 'XX-Large (32px)', value: 'fontSize32' }
        ];
        
        sizes.forEach(size => {
            const option = document.createElement('option');
            option.value = size.value;
            option.textContent = size.label;
            select.appendChild(option);
        });
        
        select.addEventListener('change', function() {
            const fontSizeAttr = this.value;
            const trixEditor = editor.editor;
            
            if (!trixEditor) return;
            
            const range = trixEditor.getSelectedRange();
            if (range[0] === range[1]) {
                alert('Please select text first');
                this.value = '';
                return;
            }
            
            // Remove all font size attributes first
            ['fontSize12', 'fontSize16', 'fontSize20', 'fontSize24', 'fontSize32'].forEach(attr => {
                if (trixEditor.attributeIsActive(attr)) {
                    trixEditor.deactivateAttribute(attr);
                }
            });
            
            // Apply new font size if selected
            if (fontSizeAttr) {
                trixEditor.activateAttribute(fontSizeAttr);
            }
            
            this.value = '';
        });
        
        fontSizeGroup.appendChild(select);
        
        // Find the container that holds all button groups
        const buttonContainer = toolbar.querySelector('.flex.gap-x-3.overflow-x-auto');
        if (buttonContainer && buttonContainer.firstChild) {
            buttonContainer.insertBefore(fontSizeGroup, buttonContainer.firstChild);
        } else {
            // Fallback: just prepend to toolbar
            if (toolbar.firstChild) {
                toolbar.insertBefore(fontSizeGroup, toolbar.firstChild);
            } else {
                toolbar.appendChild(fontSizeGroup);
            }
        }
    });
});
</script>
