import { Head, Link } from '@inertiajs/react';
import { Plus } from 'lucide-react';
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
import { create, index, show } from '@/routes/roadmaps';

type RoadmapSummary = {
    id: string;
    title: string;
    kind: string;
    status: string;
    description: string | null;
    progress: number;
    nodes_count: number;
    start_date: string | null;
    target_end_date: string | null;
};

export default function RoadmapsIndex({
    roadmaps,
}: {
    roadmaps: RoadmapSummary[];
}) {
    const t = useTranslation();

    return (
        <>
            <Head title={t('Roadmaps')} />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-semibold">
                            {t('Roadmaps')}
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            {t('Your plans and their progress.')}
                        </p>
                    </div>

                    <Button asChild>
                        <Link href={create()}>
                            <Plus />
                            {t('New roadmap')}
                        </Link>
                    </Button>
                </div>

                {roadmaps.length === 0 ? (
                    <Card>
                        <CardHeader>
                            <CardTitle>{t('No roadmaps yet')}</CardTitle>
                            <CardDescription>
                                {t(
                                    'Create your first roadmap to start planning.',
                                )}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Button asChild>
                                <Link href={create()}>
                                    {t('Create a roadmap')}
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        {roadmaps.map((roadmap) => (
                            <Link
                                key={roadmap.id}
                                href={show(roadmap.id)}
                                className="block"
                            >
                                <Card className="h-full transition-colors hover:border-primary/40">
                                    <CardHeader>
                                        <div className="flex items-start justify-between gap-2">
                                            <CardTitle className="truncate">
                                                {roadmap.title}
                                            </CardTitle>
                                            <Badge variant="secondary">
                                                {t(roadmap.kind)}
                                            </Badge>
                                        </div>
                                        <CardDescription className="line-clamp-2">
                                            {roadmap.description ??
                                                t('No description')}
                                        </CardDescription>
                                    </CardHeader>

                                    <CardContent className="space-y-2">
                                        <div className="h-2 w-full overflow-hidden rounded-full bg-muted">
                                            <div
                                                className="h-full rounded-full bg-primary transition-[width] duration-500"
                                                style={{
                                                    width: `${Math.min(100, Math.max(0, roadmap.progress))}%`,
                                                }}
                                            />
                                        </div>
                                        <p className="text-xs text-muted-foreground">
                                            {Math.round(roadmap.progress)}% ·{' '}
                                            {roadmap.nodes_count}{' '}
                                            {roadmap.nodes_count === 1
                                                ? t('node')
                                                : t('nodes')}
                                        </p>
                                    </CardContent>
                                </Card>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

RoadmapsIndex.layout = {
    breadcrumbs: [
        {
            title: 'Roadmaps',
            href: index(),
        },
    ],
};
