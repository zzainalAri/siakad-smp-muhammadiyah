import HeaderTitle from '@/Components/HeaderTitle';
import InputError from '@/Components/InputError';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconBooks, IconCheck } from '@tabler/icons-react';
import { toast } from 'sonner';

export default function Edit(props) {
    const { data, setData, post, errors, processing, reset } = useForm({
        level_id: props.course.level_id ?? null,
        teacher_id: props.course.teacher_id ?? null,
        name: props.course.name ?? '',
        semester: props.course.semester ?? 1,
        _method: props.page_setting.method,
    });

    const onHandleReset = () => {
        reset();
    };

    const onHandleSubmit = (e) => {
        e.preventDefault();
        post(props.page_setting.action, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (success) => {
                const flash = flashMessage(success);
                if (flash) toast[flash.type](flash.message);
            },
        });
    };

    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconBooks}
                    />
                    <Button asChild variant="blue" size="xl" className="w-full lg:w-auto">
                        <Link href={route('admin.courses.index')}>
                            <IconArrowLeft className="size-4" /> Kembali
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardContent className="p-6">
                        <form onSubmit={onHandleSubmit}>
                            <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                                <div className="col-span-full">
                                    <Label htmlFor="name">Nama Tingkat</Label>
                                    <Input
                                        type="text"
                                        name="name"
                                        id="name"
                                        placeholder="Masukkan Tingkat"
                                        value={data.name}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.name && <InputError message={errors.name} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="level_id">Nama Fakultas</Label>
                                    <Select
                                        defaultValue={data.level_id}
                                        onValueChange={(value) => setData('level_id', value)}
                                        id="level_id"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.levels.find((level) => level.value == data.level_id)?.label ??
                                                    'Pilih tingkat'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.levels.map((level, index) => (
                                                    <SelectItem key={index} value={level.value}>
                                                        {level.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.level_id && <InputError message={errors.level_id} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="teacher_id">Dosen</Label>
                                    <Select
                                        defaultValue={data.teacher_id}
                                        onValueChange={(value) => setData('teacher_id', value)}
                                        id="teacher_id"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.teachers.find((teacher) => teacher.value == data.teacher_id)
                                                    ?.label ?? 'Pilih dosen'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.teachers.map((teacher, index) => (
                                                    <SelectItem key={index} value={teacher.value}>
                                                        {teacher.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.teacher_id && <InputError message={errors.teacher_id} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="semester">Semester</Label>
                                    <Input
                                        type="number"
                                        name="semester"
                                        id="semester"
                                        placeholder="Masukkan sks"
                                        value={data.semester}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.semester && <InputError message={errors.semester} />}
                                </div>
                            </div>
                            <div className="mt-8 flex flex-col gap-2 lg:flex-row lg:justify-end">
                                <Button type="button" variant="ghost" size="xl" onClick={onHandleReset}>
                                    Reset
                                </Button>
                                <Button type="submit" variant="blue" size="xl" disabled={processing}>
                                    <IconCheck />
                                    Save
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

Edit.layout = (page) => <AppLayout children={page} title={page.props.page_setting.title} />;
