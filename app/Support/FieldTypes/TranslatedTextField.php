<?php

namespace App\Support\FieldTypes;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\ViewField;
use Lunar\Admin\Support\FieldTypes\BaseFieldType;
use Lunar\Admin\Support\Forms\Components\TranslatedText as TranslatedTextComponent;
use Lunar\Admin\Support\Synthesizers\TranslatedTextSynth;
use Lunar\Models\Attribute;

class TranslatedTextField extends BaseFieldType
{
    protected static string $synthesizer = TranslatedTextSynth::class;

    public static function getConfigurationFields(): array
    {
        return TextField::getConfigurationFields();
    }

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        // Use standard TranslatedText with richtext
        if ((bool) $attribute->configuration->get('richtext')) {
            return TranslatedTextComponent::make($attribute->handle)
                ->optionRichtext(true)
                ->when(filled($attribute->validation_rules), fn (TranslatedTextComponent $component) => $component->rules($attribute->validation_rules))
                ->required((bool) $attribute->required)
                ->helperText($attribute->translate('description'));
        }
        
        // Fallback to simple text input
        return TranslatedTextComponent::make($attribute->handle)
            ->when(filled($attribute->validation_rules), fn (TranslatedTextComponent $component) => $component->rules($attribute->validation_rules))
            ->required((bool) $attribute->required)
            ->helperText($attribute->translate('description'));
    }
}
