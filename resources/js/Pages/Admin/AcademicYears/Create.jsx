import HeaderTitle from '@/Components/HeaderTitle';
import InputError from '@/Components/InputError';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Checkbox } from '@/Components/ui/checkbox';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconCalendar, IconCheck } from '@tabler/icons-react';
import { toast } from 'sonner';

export default function Create(props) {
    const { data, setData, post, errors, processing, reset } = useForm({
        name: '',
        start_date: '',
        end_date: '',
        semester: null,
        is_active: false,
        _method: props.page_setting.method,
    });

    const onHandleReset = () => reset();

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
                        icon={IconCalendar}
                    />
                    <Button asChild variant="blue" size="xl" className="w-full lg:w-auto">
                        <Link href={route('admin.academic-years.index')}>
                            <IconArrowLeft className="size-4" /> Kembali
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardContent className="p-6">
                        <form onSubmit={onHandleSubmit}>
                            <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                                <div className="col-span-full">
                                    <Label htmlFor="name">Nama Program Studi</Label>
                                    <Input
                                        type="text"
                                        name="name"
                                        id="name"
                                        placeholder="Masukkan tahun ajaran. cth: 2024/2025"
                                        value={data.name}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.name && <InputError message={errors.name} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="start_date">Tanggal Dimulai</Label>
                                    <Input
                                        type="date"
                                        name="start_date"
                                        id="start_date"
                                        value={data.start_date}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.start_date && <InputError message={errors.start_date} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="end_date">Tanggal Berakhir</Label>
                                    <Input
                                        type="date"
                                        name="end_date"
                                        id="end_date"
                                        value={data.end_date}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.end_date && <InputError message={errors.end_date} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="semester">Semester</Label>
                                    <Select
                                        defaultValue={data.semester}
                                        onValueChange={(value) => setData('semester', value)}
                                        id="semester"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.academicYearSemesters.find(
                                                    (semester) => semester.value == data.semester,
                                                )?.label ?? 'Pilih semester'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.academicYearSemesters.map((semester, index) => (
                                                    <SelectItem key={index} value={semester.value}>
                                                        {semester.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.semester && <InputError message={errors.semester} />}
                                </div>
                                <div className="col-span-full">
                                    <div className="flex items-start space-x-2">
                                        <Checkbox
                                            id="is_active"
                                            name="is_active"
                                            checked={data.is_active}
                                            onCheckedChange={(checked) => setData('is_active', checked)}
                                        />

                                        <div className="gap-1/5 grid leading-none">
                                            <Label htmlFor="is_active">Apakah Aktif?</Label>
                                        </div>
                                    </div>
                                    {errors.is_active && <InputError message={errors.is_active} />}
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

Create.layout = (page) => <AppLayout children={page} title={page.props.page_setting.title} />;
