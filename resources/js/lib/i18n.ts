import { usePage } from '@inertiajs/react';

export type TranslationFunction = (key: string) => string;

/**
 * Translate a string using the dictionary shared by the server.
 * English keys fall through unchanged.
 */
export function useTranslation(): TranslationFunction {
    const { translations } = usePage().props;

    return (key: string) => translations[key] ?? key;
}
