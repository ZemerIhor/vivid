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
document.addEventListener('DOMContentLoaded', function() {
    // Add font size control to all Trix editors
    document.addEventListener('trix-initialize', function(event) {
        const editor = event.target;
        const toolbar = editor.previousElementSibling;
        
        if (!toolbar || toolbar.classList.contains('trix-font-size-added')) {
            return;
        }
        
        toolbar.classList.add('trix-font-size-added');
        
        // Create font size dropdown
        const fontSizeGroup = document.createElement('span');
        fontSizeGroup.className = 'font-size-dropdown';
        fontSizeGroup.setAttribute('data-trix-button-group', 'font-tools');
        
        const select = document.createElement('select');
        select.title = 'Font Size';
        
        const sizes = [
            { label: 'Normal', value: '' },
            { label: 'Small', value: '12px' },
            { label: 'Medium', value: '16px' },
            { label: 'Large', value: '20px' },
            { label: 'X-Large', value: '24px' },
            { label: 'XX-Large', value: '32px' }
        ];
        
        sizes.forEach(size => {
            const option = document.createElement('option');
            option.value = size.value;
            option.textContent = size.label;
            select.appendChild(option);
        });
        
        select.addEventListener('change', function() {
            const fontSize = this.value;
            const trixEditor = editor.editor;
            
            if (!trixEditor) return;
            
            const range = trixEditor.getSelectedRange();
            if (range[0] === range[1]) {
                alert('Please select text first');
                this.value = '';
                return;
            }
            
            // Get selected HTML
            const selectedDocument = trixEditor.getDocument().getDocumentAtRange(range);
            const selectedHTML = selectedDocument.toString();
            
            // Wrap in span with font-size style
            let newHTML;
            if (fontSize) {
                newHTML = '<span style="font-size: ' + fontSize + '">' + selectedHTML + '</span>';
            } else {
                // Remove font-size styling
                newHTML = selectedHTML.replace(/<span[^>]*style="[^"]*font-size:[^"]*"[^>]*>(.*?)<\/span>/gi, '$1');
            }
            
            // Replace selection
            trixEditor.setSelectedRange(range);
            trixEditor.deleteInDirection('forward');
            trixEditor.insertHTML(newHTML);
            
            this.value = '';
        });
        
        fontSizeGroup.appendChild(select);
        
        // Insert font size dropdown at the beginning of toolbar
        const firstButtonGroup = toolbar.querySelector('[data-trix-button-group]');
        if (firstButtonGroup) {
            toolbar.insertBefore(fontSizeGroup, firstButtonGroup);
        } else {
            toolbar.appendChild(fontSizeGroup);
        }
    });
});
</script>
