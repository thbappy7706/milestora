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
import { index, store } from '@/routes/roadmaps';

type KindOption = {
    value: string;
    label: string;
};

const fieldClassName =
    'border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]';

export default function CreateRoadmap({ kinds }: { kinds: KindOption[] }) {
    return (
        <>
            <Head title="New roadmap" />

            <div className="mx-auto w-full max-w-2xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle>New roadmap</CardTitle>
                        <CardDescription>
                            Set up the basics now. You can add phases and tasks
                            once it exists.
                        </CardDescription>
                    </CardHeader>

                    <CardContent>
                        <Form {...store.form()} className="space-y-6">
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="title">Title</Label>
                                        <Input
                                            id="title"
                                            name="title"
                                            required
                                            autoFocus
                                            placeholder="e.g. LLM/NLP Engineer in 9 months"
                                        />
                                        <InputError message={errors.title} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="description">
                                            Description
                                        </Label>
                                        <textarea
                                            id="description"
                                            name="description"
                                            rows={4}
                                            placeholder="What is this roadmap for?"
                                            className={`${fieldClassName} h-auto py-2`}
                                        />
                                        <InputError
                                            message={errors.description}
                                        />
                                    </div>

                                    <div className="grid gap-4 sm:grid-cols-2">
                                        <div className="grid gap-2">
                                            <Label htmlFor="kind">Kind</Label>
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
                                                    >
                                                        {kind.label}
                                                    </option>
                                                ))}
                                            </select>
                                            <InputError message={errors.kind} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="daily_goal_minutes">
                                                Daily goal (minutes)
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
                                                Start date
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
                                                Target end date
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
                                            Create roadmap
                                        </Button>
                                        <Button variant="ghost" asChild>
                                            <Link href={index()}>Cancel</Link>
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
