import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import UseFilter from '@/hooks/UseFilter';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { IconBooks, IconRefresh } from '@tabler/icons-react';
import { useState } from 'react';

export default function Index(props) {
    const { data: courses, meta, links } = props.courses;
    const [params, setParams] = useState(props.state);

    const onSortable = (field) => {
        setParams({
            ...params,
            field: field,
            direction: params.direction === 'asc' ? 'desc' : 'asc',
        });
    };
    UseFilter({
        route: route('teachers.courses.index'),
        values: params,
        only: ['courses'],
    });

    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconBooks}
                    />
                </div>
                <Card>
                    <CardHeader className="mb-4 p-0">
                        {/* Filters */}

                        <div className="flex w-full flex-col gap-4 px-6 py-4 lg:flex-row lg:items-center">
                            <Input
                                className="w-full sm:w-1/4"
                                placeholder="search"
                                value={params?.search}
                                onChange={(e) => setParams((prev) => ({ ...prev, search: e.target.value }))}
                            />
                            <Select value={params?.load} onValueChange={(e) => setParams({ ...params, load: e })}>
                                <SelectTrigger className="w-full sm:w-24">
                                    <SelectValue placeholder="Load" />
                                </SelectTrigger>
                                <SelectContent>
                                    {[9, 23, 49, 76, 99].map((number, index) => (
                                        <SelectItem key={index} value={number}>
                                            {number}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <Button variant="red" onClick={() => setParams(props.state)} size="xl">
                                <IconRefresh className="size-4" />
                                Bersihkan
                            </Button>
                        </div>
                        {/* show filter */}
                        <ShowFilter params={params} />
                    </CardHeader>

                    <CardContent>
                        {courses.length === 0 ? (
                            <EmptyState
                                icon={IconBooks}
                                title="Tidak ada Mata Pelajaran"
                                subtitle="Mulailah dengan membuat Mata Pelajaran baru"
                            />
                        ) : (
                            <ul role="list" className="grid grid-cols-1 gap-5 px-2 pb-2 lg:grid-cols-3">
                                {courses.map((course, index) => (
                                    <li
                                        key={index}
                                        className="group overflow-hidden rounded-xl border border-secondary"
                                    >
                                        <Link
                                            href={route('teachers.courses.show', [course])}
                                            className="text-sm font-semibold leading-relaxed text-foreground"
                                        >
                                            <div className="flex items-center justify-between gap-x-4 bg-gray-50 p-6 group-hover:bg-gray-100 group-hover:transition-all">
                                                {course.name}
                                            </div>
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </CardContent>
                    <CardFooter className="flex w-full flex-col items-center justify-between gap-y-2 border-t py-3 lg:flex-row">
                        <p className="text-sm text-muted-foreground">
                            Menampilkan <span className="font-medium text-blue-600">{meta.to ?? 0}</span> dari{' '}
                            {meta.total} Mata Pelajaran
                        </p>
                        <div className="overflow-x-auto">
                            {meta.has_pages && <PaginationTable meta={meta} links={links} />}
                        </div>
                    </CardFooter>
                </Card>
            </div>
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} title={page.props.page_setting.title} />;
