import { Head, Link } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useTranslation } from '@/lib/i18n';
import { index } from '@/routes/roadmaps';

type RoadmapDetail = {
    id: string;
    title: string;
    kind: string;
    status: string;
    description: string | null;
    progress: number;
    nodes_count: number;
    daily_goal_minutes: number;
    start_date: string | null;
    target_end_date: string | null;
};

export default function ShowRoadmap({ roadmap }: { roadmap: RoadmapDetail }) {
    const t = useTranslation();

    return (
        <>
            <Head title={roadmap.title} />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <div className="space-y-1">
                        <div className="flex flex-wrap items-center gap-2">
                            <h1 className="text-2xl font-semibold">
                                {roadmap.title}
                            </h1>
                            <Badge variant="secondary">{t(roadmap.kind)}</Badge>
                            <Badge variant="outline">{t(roadmap.status)}</Badge>
                        </div>
                        <p className="max-w-2xl text-sm text-muted-foreground">
                            {roadmap.description ?? t('No description yet.')}
                        </p>
                    </div>

                    <Button variant="outline" asChild>
                        <Link href={index()}>{t('All roadmaps')}</Link>
                    </Button>
                </div>

                <div className="grid gap-4 sm:grid-cols-3">
                    <Card>
                        <CardHeader>
                            <CardDescription>{t('Progress')}</CardDescription>
                            <CardTitle className="text-2xl">
                                {Math.round(roadmap.progress)}%
                            </CardTitle>
                        </CardHeader>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardDescription>{t('Nodes')}</CardDescription>
                            <CardTitle className="text-2xl">
                                {roadmap.nodes_count}
                            </CardTitle>
                        </CardHeader>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardDescription>{t('Daily goal')}</CardDescription>
                            <CardTitle className="text-2xl">
                                {roadmap.daily_goal_minutes} {t('min')}
                            </CardTitle>
                        </CardHeader>
                    </Card>
                </div>

                <Card className="flex-1">
                    <CardHeader>
                        <CardTitle>{t('Roadmap tree')}</CardTitle>
                        <CardDescription>
                            {roadmap.start_date || roadmap.target_end_date
                                ? `${roadmap.start_date ?? '—'} → ${roadmap.target_end_date ?? '—'}`
                                : t('No dates set.')}
                        </CardDescription>
                    </CardHeader>

                    <CardContent>
                        <div className="flex min-h-48 flex-col items-center justify-center gap-2 rounded-lg border border-dashed p-8 text-center">
                            <p className="text-sm font-medium">
                                {t('This roadmap is empty.')}
                            </p>
                            <p className="max-w-sm text-sm text-muted-foreground">
                                {t(
                                    'The tree editor arrives in the next phase. For now the roadmap is created and ready for nodes.',
                                )}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

ShowRoadmap.layout = {
    breadcrumbs: [
        {
            title: 'Roadmaps',
            href: index(),
        },
    ],
};
