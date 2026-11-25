import { Link, usePage } from '@inertiajs/react';
import { CheckCircle2, Map, Milestone, TrendingUp } from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import { AppearanceToggle } from '@/components/appearance-toggle';
import { LanguageSwitcher } from '@/components/language-switcher';
import { useTranslation } from '@/lib/i18n';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types';

const features = [
    {
        icon: Map,
        title: 'Map every milestone',
        description:
            'Break a big goal into phases, sections, and tasks you can actually finish.',
    },
    {
        icon: Milestone,
        title: 'Track skills, not just tasks',
        description:
            'Checkpoints and projects show what you have truly learned along the way.',
    },
    {
        icon: TrendingUp,
        title: 'See progress add up',
        description:
            'Weighted progress, streaks, and activity history keep momentum visible.',
    },
];

const previewPhases = [
    { title: 'Foundations', meta: '12 / 12', width: 'w-full' },
    { title: 'Databases', meta: '8 / 12', width: 'w-2/3' },
    { title: 'APIs & auth', meta: '3 / 10', width: 'w-1/4' },
];

export default function AuthSplitLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    const { name } = usePage().props;
    const t = useTranslation();

    return (
        <div className="min-h-svh bg-background lg:grid lg:grid-cols-2">
            {/* Purpose panel */}
            <div className="relative hidden overflow-hidden bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-10 text-white lg:flex lg:flex-col">
                <div className="pointer-events-none absolute -top-24 -right-24 size-96 rounded-full bg-white/10 blur-3xl" />
                <div className="pointer-events-none absolute -bottom-32 -left-20 size-96 rounded-full bg-fuchsia-400/20 blur-3xl" />

                <Link
                    href={home()}
                    className="relative z-10 flex items-center gap-2.5 text-lg font-semibold tracking-tight"
                >
                    <span className="flex size-9 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/25 backdrop-blur-sm">
                        <AppLogoIcon className="size-5 fill-current" />
                    </span>
                    {name}
                </Link>

                <div className="relative z-10 mt-16 max-w-md">
                    <p className="text-xs font-semibold tracking-[0.2em] text-white/70 uppercase">
                        {t('Roadmaps that stick')}
                    </p>
                    <h1 className="mt-4 text-3xl leading-tight font-semibold text-balance">
                        {t('Turn big goals into visible, daily progress.')}
                    </h1>
                    <p className="mt-4 text-sm leading-relaxed text-white/75">
                        {t(
                            'Your learning plan, skill checkpoints, and study history in one calm workspace.',
                        )}
                    </p>

                    <ul className="mt-8 space-y-5">
                        {features.map(({ icon: Icon, title, description }) => (
                            <li key={title} className="flex gap-3">
                                <span className="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-lg bg-white/10 ring-1 ring-white/20">
                                    <Icon className="size-4" />
                                </span>
                                <div>
                                    <p className="text-sm font-medium">
                                        {t(title)}
                                    </p>
                                    <p className="mt-0.5 text-xs leading-relaxed text-white/65">
                                        {t(description)}
                                    </p>
                                </div>
                            </li>
                        ))}
                    </ul>
                </div>

                {/* Roadmap preview */}
                <div className="relative z-10 mt-auto max-w-md rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-md">
                    <div className="flex items-center justify-between text-sm">
                        <span className="flex items-center gap-2 font-medium">
                            <CheckCircle2 className="size-4" />
                            {t('Full-stack developer')}
                        </span>
                        <span className="text-white/70">68%</span>
                    </div>

                    <div className="mt-3 h-1.5 overflow-hidden rounded-full bg-white/20">
                        <div className="h-full w-[68%] rounded-full bg-white" />
                    </div>

                    <div className="mt-4 space-y-3">
                        {previewPhases.map((phase) => (
                            <div
                                key={phase.title}
                                className="flex items-center gap-3 text-xs"
                            >
                                <span className="w-24 shrink-0 text-white/80">
                                    {t(phase.title)}
                                </span>
                                <span className="h-1 flex-1 overflow-hidden rounded-full bg-white/15">
                                    <span
                                        className={`block h-full rounded-full bg-white/70 ${phase.width}`}
                                    />
                                </span>
                                <span className="w-14 text-right text-white/60">
                                    {phase.meta}
                                </span>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Form panel */}
            <div className="flex min-h-svh flex-col">
                <div className="flex items-center justify-between p-4 sm:px-8">
                    <Link
                        href={home()}
                        className="flex items-center gap-2 font-semibold tracking-tight lg:hidden"
                    >
                        <span className="flex size-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500 text-white">
                            <AppLogoIcon className="size-4 fill-current" />
                        </span>
                        {name}
                    </Link>

                    <div className="ml-auto flex items-center gap-1">
                        <LanguageSwitcher />
                        <AppearanceToggle />
                    </div>
                </div>

                <div className="flex flex-1 items-center justify-center px-4 pb-12 sm:px-8">
                    <div className="w-full max-w-sm space-y-6">
                        <div className="space-y-1.5">
                            <h2 className="text-2xl font-semibold tracking-tight">
                                {title}
                            </h2>
                            <p className="text-sm text-balance text-muted-foreground">
                                {description}
                            </p>
                        </div>

                        {children}
                    </div>
                </div>
            </div>
        </div>
    );
}
