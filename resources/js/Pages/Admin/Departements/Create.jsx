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
import { IconArrowLeft, IconCheck, IconSchool } from '@tabler/icons-react';
import { toast } from 'sonner';

export default function Create(props) {
    const { data, setData, post, errors, processing, reset } = useForm({
        faculty_id: null,
        name: '',
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
                        icon={IconSchool}
                    />
                    <Button asChild variant="blue" size="xl" className="w-full lg:w-auto">
                        <Link href={route('admin.departements.index')}>
                            <IconArrowLeft className="size-4" /> Kembali
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardContent className="p-6">
                        <form onSubmit={onHandleSubmit}>
                            <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                                <div className="col-span-full">
                                    <Label htmlFor="faculty_id">Nama Fakultas</Label>
                                    <Select
                                        defaultValue={data.faculty_id}
                                        onValueChange={(value) => setData('faculty_id', value)}
                                        id="faculty_id"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.faculties.find((faculty) => faculty.value == data.faculty_id)
                                                    ?.label ?? 'Pilih fakultas'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.faculties.map((faculty, index) => (
                                                    <SelectItem key={index} value={faculty.value}>
                                                        {faculty.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.faculty_id && <InputError message={errors.faculty_id} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="name">Nama Program Studi</Label>
                                    <Input
                                        type="text"
                                        name="name"
                                        id="name"
                                        placeholder="Masukkan nama fakultas"
                                        value={data.name}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.name && <InputError message={errors.name} />}
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
