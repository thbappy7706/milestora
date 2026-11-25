import { usePage } from '@inertiajs/react';

import AppLogoIcon from '@/components/app-logo-icon';
import { useTranslation } from '@/lib/i18n';

export default function AppLogo() {
    const { name } = usePage().props;
    const t = useTranslation();

    return (
        <>
            <div className="flex aspect-square size-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500 text-white shadow-md shadow-violet-500/25">
                <AppLogoIcon className="size-5 fill-current" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm group-data-[collapsible=icon]:hidden">
                <span className="truncate text-[13px] leading-tight font-semibold tracking-tight">
                    {name}
                </span>
                <span className="truncate text-[11px] font-normal text-muted-foreground">
                    {t('Roadmap & skill tracker')}
                </span>
            </div>
        </>
    );
}
