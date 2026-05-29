<?php

namespace App\Rules;

use App\Helpers\VideoUrlHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Regra de validação que garante que a URL informada pertence
 * a um dos domínios de vídeo externo permitidos (YouTube, Vimeo, etc.)
 * e que o formato da URL pode ser convertido em embed.
 */
class ExternalVideoUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // nullable é tratado na regra do campo
        }

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('A URL informada não é válida.');
            return;
        }

        if (!VideoUrlHelper::isAllowed($value)) {
            $fail('A URL deve ser do YouTube (youtube.com, youtu.be) ou Vimeo (vimeo.com).');
            return;
        }

        if (VideoUrlHelper::toEmbedUrl($value) === null) {
            $fail('Não foi possível identificar o vídeo na URL informada. Verifique se é um link de vídeo válido.');
        }
    }
}
