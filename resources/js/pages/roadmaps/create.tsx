import { Form, Head, Link } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslation } from '@/lib/i18n';
import { index, store } from '@/routes/roadmaps';

type KindOption = {
    value: string;
    label: string;
};

const fieldClassName =
    'border-input flex h-9 w-full rounded-md border bg-background px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] dark:bg-neutral-900 dark:text-neutral-100';

export default function CreateRoadmap({ kinds }: { kinds: KindOption[] }) {
    const t = useTranslation();

    return (
        <>
            <Head title={t('New roadmap')} />

            <div className="mx-auto w-full max-w-2xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle>{t('New roadmap')}</CardTitle>
                        <CardDescription>
                            {t(
                                'Set up the basics now. You can add phases and tasks once it exists.',
                            )}
                        </CardDescription>
                    </CardHeader>

                    <CardContent>
                        <Form {...store.form()} className="space-y-6">
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="title">
                                            {t('Title')}
                                        </Label>
                                        <Input
                                            id="title"
                                            name="title"
                                            required
                                            autoFocus
                                            placeholder={t(
                                                'e.g. LLM/NLP Engineer in 9 months',
                                            )}
                                        />
                                        <InputError message={errors.title} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="description">
                                            {t('Description')}
                                        </Label>
                                        <textarea
                                            id="description"
                                            name="description"
                                            rows={4}
                                            placeholder={t(
                                                'What is this roadmap for?',
                                            )}
                                            className={`${fieldClassName} h-auto py-2`}
                                        />
                                        <InputError
                                            message={errors.description}
                                        />
                                    </div>

                                    <div className="grid gap-4 sm:grid-cols-2">
                                        <div className="grid gap-2">
                                            <Label htmlFor="kind">
                                                {t('Kind')}
                                            </Label>
                                            <select
                                                id="kind"
                                                name="kind"
                                                defaultValue="learning"
                                                className={fieldClassName}
                                            >
                                                {kinds.map((kind) => (
                                                    <option
                                                        key={kind.value}
                                                        value={kind.value}
                                                        className="bg-background text-foreground dark:bg-neutral-900 dark:text-neutral-100"
                                                    >
                                                        {t(kind.label)}
                                                    </option>
                                                ))}
                                            </select>
                                            <InputError message={errors.kind} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="daily_goal_minutes">
                                                {t('Daily goal (minutes)')}
                                            </Label>
                                            <Input
                                                id="daily_goal_minutes"
                                                name="daily_goal_minutes"
                                                type="number"
                                                min={0}
                                                max={1440}
                                                defaultValue={120}
                                                required
                                            />
                                            <InputError
                                                message={
                                                    errors.daily_goal_minutes
                                                }
                                            />
                                        </div>
                                    </div>

                                    <div className="grid gap-4 sm:grid-cols-2">
                                        <div className="grid gap-2">
                                            <Label htmlFor="start_date">
                                                {t('Start date')}
                                            </Label>
                                            <Input
                                                id="start_date"
                                                name="start_date"
                                                type="date"
                                            />
                                            <InputError
                                                message={errors.start_date}
                                            />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="target_end_date">
                                                {t('Target end date')}
                                            </Label>
                                            <Input
                                                id="target_end_date"
                                                name="target_end_date"
                                                type="date"
                                            />
                                            <InputError
                                                message={errors.target_end_date}
                                            />
                                        </div>
                                    </div>

                                    <div className="flex items-center gap-3">
                                        <Button disabled={processing}>
                                            {t('Create roadmap')}
                                        </Button>
                                        <Button variant="ghost" asChild>
                                            <Link href={index()}>
                                                {t('Cancel')}
                                            </Link>
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

CreateRoadmap.layout = {
    breadcrumbs: [
        {
            title: 'Roadmaps',
            href: index(),
        },
        {
            title: 'New roadmap',
            href: store.url(),
        },
    ],
};
