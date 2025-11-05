@php
    // Ensures a stable unique ID for the editor instance
    $editorId = $getId();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-load="visible || event (ax-modal-opened)"
        x-load-src="https://cdn.ckeditor.com/ckeditor5/43.0.0/super-build/ckeditor.js"
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }},
            editor: null,
            initEditor() {
                const el = this.$refs.editor;
                const wait = () => {
                    if (window.ClassicEditor?.create) {
                        this.createEditor(el);
                    } else {
                        setTimeout(wait, 50);
                    }
                };
                wait();
            },
            createEditor(el) {
                if (this.editor) return;
                window.ClassicEditor
                    .create(el, {
                        toolbar: {
                            items: [
                                'undo','redo','|',
                                'heading','|',
                                'bold','italic','underline','strikethrough','subscript','superscript','|',
                                'link','blockQuote','codeBlock','|',
                                'bulletedList','numberedList','outdent','indent','|',
                                'alignment','removeFormat','|',
                                'fontSize','fontFamily','fontColor','fontBackgroundColor'
                            ],
                            shouldNotGroupWhenFull: true,
                        },
                        fontFamily: {
                            supportAllValues: true,
                            options: [
                                'default',
                                'Arial, Helvetica, sans-serif',
                                'Roboto, sans-serif',
                                'Inter, system-ui, Avenir, Helvetica, Arial, sans-serif',
                                'Georgia, serif',
                                'Times New Roman, Times, serif',
                                'Courier New, Courier, monospace',
                            ],
                        },
                        removePlugins: [
                            // Remove cloud/integration plugins that may require keys or backends
                            'CKBox','CKFinder','EasyImage','RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges',
                            'RealTimeCollaborativeRevisionHistory','PresenceList','Comments','TrackChanges','RevisionHistory','WProofreader'
                        ],
                    })
                    .then(ed => {
                        this.editor = ed;
                        ed.setData(this.state ?? '');
                        ed.model.document.on('change:data', () => {
                            this.state = ed.getData();
                        });
                    })
                    .catch(console.error);
            }
        }"
        x-init="initEditor()"
        wire:ignore.self
    >
        <textarea x-ref="editor" id="{{ $editorId }}" x-bind:class="{ 'hidden': !!editor }">{!! $getState() !!}</textarea>
    </div>
</x-dynamic-component>
