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
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

export default function Create(props) {
    const { data, setData, post, errors, processing, reset } = useForm({
        level_id: null,
        level_name: null,
        academic_year_id: props.academic_year.name,
        name: '',
        _method: props.page_setting.method,
    });
    const [filteredClassrooms, setFilteredClassrooms] = useState([]);

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

    console.log(data);
    console.log(props.classrooms);

    useEffect(() => {
        const classroom = props.classrooms?.[data.level_name] ?? [];
        setFilteredClassrooms(classroom);
    }, [data.level_id]);

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_setting.title}
                    subtitle={props.page_setting.subtitle}
                    icon={IconSchool}
                />
                <Button asChild variant="blue" size="xl" className="w-full lg:w-auto">
                    <Link href={route('admin.classrooms.index')}>
                        <IconArrowLeft className="size-4" /> Kembali
                    </Link>
                </Button>
            </div>
            <Card>
                <CardContent className="p-6">
                    <form onSubmit={onHandleSubmit}>
                        <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                            <div className="col-span-full">
                                <Label htmlFor="level_id">Tingkat</Label>
                                <Select
                                    defaultValue={data.level_id}
                                    onValueChange={(value) => {
                                        setData('level_id', value);
                                        const selectedLevel = props.levels.find((level) => level.value == value);
                                        if (selectedLevel) {
                                            setData('level_name', selectedLevel.label);
                                        }
                                    }}
                                    id="level_id"
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.levels.find((level) => level.value == data.level_id)?.label ??
                                                'Pilih tingkat'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.levels.map((level, index) => (
                                            <SelectItem key={index} value={level.value}>
                                                {level.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.level_id && <InputError message={errors.level_id} />}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="academic_year_id">Tahun Ajaran</Label>
                                <Input
                                    id="academic_year_id"
                                    name="academic_year_id"
                                    type="text"
                                    value={data.academic_year_id}
                                    disabled
                                    className="hover:cursor-not-allowed"
                                />
                                {errors.academic_year_id && <InputError message={errors.academic_year_id} />}
                            </div>
                            <div className="col-span-full">
                                <Label htmlFor="name">Nama Kelas</Label>
                                <Select
                                    defaultValue={data.name}
                                    onValueChange={(value) => setData('name', value)}
                                    id="name"
                                    disabled={!data.level_id}
                                >
                                    <SelectTrigger>
                                        <SelectValue>{data.name || 'Pilih Nama Kelas'}</SelectValue>
                                    </SelectTrigger>
                                    <SelectContent side="bottom">
                                        {filteredClassrooms.map((kelas, index) => (
                                            <SelectItem key={index} value={kelas.value}>
                                                {kelas.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>

                                {errors.name && <InputError message={errors.name} />}
                            </div>
                        </div>
                        <div className="mt-8 flex flex-col gap-2 lg:flex-row lg:justify-end">
                            <Button type="button" variant="ghost" size="xl" onClick={onHandleReset}>
                                Reset
                            </Button>
                            <Button type="submit" variant="blue" size="xl" disabled={processing}>
                                <IconCheck />
                                Simpan
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    );
}

Create.layout = (page) => <AppLayout children={page} title={page.props.page_setting.title} />;
