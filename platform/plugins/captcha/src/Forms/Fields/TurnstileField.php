<?php

namespace Botble\Captcha\Forms\Fields;

use Botble\Base\Forms\FormField;

class TurnstileField extends FormField
{
    protected function getTemplate(): string
    {
        return 'plugins/captcha::forms.fields.turnstile';
    }
}
