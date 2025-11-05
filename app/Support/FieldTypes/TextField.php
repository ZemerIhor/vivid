<?php

namespace App\Support\FieldTypes;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Lunar\Admin\Support\FieldTypes\BaseFieldType;
use Lunar\Admin\Support\Synthesizers\TextSynth;
use Lunar\Models\Attribute;

class TextField extends BaseFieldType
{
    protected static string $synthesizer = TextSynth::class;

    public static function getConfigurationFields(): array
    {
        // Reuse Lunar's "richtext" toggle from the core TextField configuration
        return [
            \Filament\Forms\Components\Toggle::make('richtext')
                ->label(__('lunarpanel::fieldtypes.text.form.richtext.label')),
        ];
    }

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        // If attribute is configured as rich text, render CKEditor via a custom view field
        if ($attribute->configuration->get('richtext')) {
            return ViewField::make($attribute->handle)
                ->view('filament.forms.components.ckeditor')
                ->helperText($attribute->translate('description'))
                ->required((bool) $attribute->required);
        }

        // Fallback to plain text input when richtext is disabled
        return TextInput::make($attribute->handle)
            ->when(filled($attribute->validation_rules), fn (TextInput $component) => $component->rules($attribute->validation_rules))
            ->required((bool) $attribute->required)
            ->helperText($attribute->translate('description'));
    }
}
