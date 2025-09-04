import AlertAction from '@/Components/AlertAction';
import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import AppLayout from '@/Layouts/AppLayout';
import hasAnyPermissions, { deleteAction, formatDateIndo } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { IconArrowsDownUp, IconPencil, IconPlus, IconRefresh, IconTrash, IconUsersGroup } from '@tabler/icons-react';
import { useState } from 'react';

export default function Index(props) {
    const { data: teachers, meta, links } = props.teachers;
    const [params, setParams] = useState(props.state);

    const onSortable = (field) => {
        setParams({
            ...params,
            field: field,
            direction: params.direction === 'asc' ? 'desc' : 'asc',
        });
    };
    UseFilter({
        route: route('admin.teachers.index'),
        values: params,
        only: ['teachers'],
    });

    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconUsersGroup}
                    />
                    {hasAnyPermissions(props.auth.permissions, ['teachers.create']) && (
                        <Button asChild variant="blue" size="xl" className="w-full lg:w-auto">
                            <Link href={route('admin.teachers.create')}>
                                <IconPlus className="size-4" /> Tambah
                            </Link>
                        </Button>
                    )}
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
                                    {[10, 23, 50, 75, 100].map((number, index) => (
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

                    <CardContent className="[&-td]: p-0 [&-td]:whitespace-nowrap [&-th]:px-6">
                        {teachers.length === 0 ? (
                            <EmptyState
                                icon={IconUsersGroup}
                                title="Tidak ada Guru"
                                subtitle="Mulailah dengan membuat Guru baru"
                            />
                        ) : (
                            <Table className="w-full">
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('id')}
                                            >
                                                #
                                                <span className="ml-2 flex-none rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('name')}
                                            >
                                                Nama
                                                <span className="ml-2 flex-none rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('email')}
                                            >
                                                Email
                                                <span className="ml-2 flex-none rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('level_id')}
                                            >
                                                Tingkat
                                                <span className="ml-2 flex-none rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('nip')}
                                            >
                                                NIP
                                                <span className="ml-2 flex-none rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('academic_title')}
                                            >
                                                Jabatan Akademik
                                                <span className="ml-2 flex-none rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button
                                                variant="ghost"
                                                className="group inline-flex"
                                                onClick={() => onSortable('created_at')}
                                            >
                                                Dibuat Pada
                                                <span className="ml-2 flex-none rounded text-muted-foreground">
                                                    <IconArrowsDownUp className="size-4" />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        {hasAnyPermissions(props.auth.permissions, [
                                            'teachers.delete',
                                            'teachers.update',
                                        ]) && <TableHead>Aksi</TableHead>}
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {teachers.map((teacher, index) => (
                                        <TableRow key={index}>
                                            <TableCell>{index + 1 + (meta.current_page - 1) * meta.per_page}</TableCell>
                                            <TableCell className="flex items-center gap-2">
                                                <Avatar>
                                                    <AvatarImage src={teacher.user?.avatar} />
                                                    <AvatarFallback>
                                                        {teacher.user?.name.substring(0, 1)}
                                                    </AvatarFallback>
                                                </Avatar>
                                                <span>{teacher.user?.name}</span>
                                            </TableCell>
                                            <TableCell>{teacher.user?.email}</TableCell>
                                            <TableCell>{teacher.level.name}</TableCell>
                                            <TableCell>{teacher.nip}</TableCell>
                                            <TableCell>{teacher.academic_title}</TableCell>
                                            <TableCell>{formatDateIndo(teacher.created_at)}</TableCell>
                                            {hasAnyPermissions(props.auth.permissions, [
                                                'teachers.update',
                                                'teachers.delete',
                                            ]) && (
                                                <TableCell>
                                                    <div className="flex items-center gap-x-1">
                                                        {hasAnyPermissions(props.auth.permissions, [
                                                            'teachers.update',
                                                        ]) && (
                                                            <Button variant="blue" size="sm" asChild>
                                                                <Link href={route('admin.teachers.edit', [teacher])}>
                                                                    <IconPencil size="4" />
                                                                    Edit
                                                                </Link>
                                                            </Button>
                                                        )}
                                                        {hasAnyPermissions(props.auth.permissions, [
                                                            'teachers.delete',
                                                        ]) && (
                                                            <AlertAction
                                                                trigger={
                                                                    <Button variant="red" size="sm">
                                                                        <IconTrash className="size-4" />
                                                                        Delete
                                                                    </Button>
                                                                }
                                                                action={() =>
                                                                    deleteAction(
                                                                        route('admin.teachers.destroy', [teacher]),
                                                                    )
                                                                }
                                                            />
                                                        )}
                                                    </div>
                                                </TableCell>
                                            )}
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}
                    </CardContent>
                    <CardFooter className="flex w-full flex-col items-center justify-between gap-y-2 border-t py-3 lg:flex-row">
                        <p className="text-sm text-muted-foreground">
                            Menampilkan <span className="font-medium text-blue-600">{meta.to ?? 0}</span> dari{' '}
                            {meta.total} Guru
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
