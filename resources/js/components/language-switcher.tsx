import { router, usePage } from '@inertiajs/react';
import { Check, Globe2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useTranslation } from '@/lib/i18n';
import { cn } from '@/lib/utils';
import { update as updateLocale } from '@/routes/locale';

const locales = [
    { value: 'en', label: 'English', short: 'EN' },
    { value: 'bn', label: 'বাংলা', short: 'বাংলা' },
] as const;

type Props = {
    align?: 'start' | 'center' | 'end';
    className?: string;
};

export function LanguageSwitcher({ align = 'end', className }: Props) {
    const { locale } = usePage().props;
    const t = useTranslation();

    const changeLocale = (value: 'en' | 'bn') => {
        if (value === locale) {
            return;
        }

        router.post(
            updateLocale.url(),
            { locale: value },
            { preserveScroll: true },
        );
    };

    const current = locales.find((item) => item.value === locale);

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="ghost"
                    size="sm"
                    aria-label={t('Language')}
                    className={cn(
                        'h-8 gap-1.5 px-2 text-muted-foreground hover:text-foreground',
                        className,
                    )}
                >
                    <Globe2 className="size-4" />
                    <span className="text-xs font-semibold tracking-wide group-data-[collapsible=icon]:hidden">
                        {current?.short ?? 'EN'}
                    </span>
                </Button>
            </DropdownMenuTrigger>

            <DropdownMenuContent align={align} className="w-44">
                <DropdownMenuLabel>{t('Language')}</DropdownMenuLabel>
                <DropdownMenuSeparator />

                {locales.map((option) => (
                    <DropdownMenuItem
                        key={option.value}
                        onSelect={() => changeLocale(option.value)}
                        className="justify-between"
                    >
                        <span>{option.label}</span>
                        {locale === option.value && (
                            <Check className="size-4 text-primary" />
                        )}
                    </DropdownMenuItem>
                ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
