import HeaderTitle from '@/Components/HeaderTitle';
import InputError from '@/Components/InputError';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Textarea } from '@/Components/ui/textarea';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconCheck, IconUsers } from '@tabler/icons-react';
import { useRef } from 'react';
import { toast } from 'sonner';

export default function Create(props) {
    const fileInputAvatar = useRef(null);

    const { data, setData, post, errors, processing, reset } = useForm({
        level_id: null,
        classroom_id: null,
        name: '',
        email: '',
        password: '',
        avatar: null,
        nisn: '',
        gender: null,
        status: null,
        batch: '',
        address: '',
        _method: props.page_setting.method,
    });

    const onHandleReset = () => {
        reset();
        fileInputAvatar.current.value = null;
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
                        icon={IconUsers}
                    />
                    <Button asChild variant="blue" size="xl" className="w-full lg:w-auto">
                        <Link href={route('admin.students.index')}>
                            <IconArrowLeft className="size-4" /> Kembali
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardContent className="p-6">
                        <form onSubmit={onHandleSubmit}>
                            <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                                <div className="col-span-full">
                                    <Label htmlFor="name">Nama Siswa</Label>
                                    <Input
                                        type="text"
                                        name="name"
                                        id="name"
                                        placeholder="Masukkan nama Siswa"
                                        value={data.name}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.name && <InputError message={errors.name} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="email">Email</Label>
                                    <Input
                                        type="email"
                                        name="email"
                                        id="email"
                                        placeholder="Masukkan alamat email"
                                        value={data.email}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.email && <InputError message={errors.email} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="password">Password</Label>
                                    <Input
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="*********"
                                        value={data.password}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.password && <InputError message={errors.password} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="level_id">Tingkat</Label>
                                    <Select
                                        defaultValue={data.level_id}
                                        onValueChange={(value) => setData('level_id', value)}
                                        id="level_id"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.levels.find((level) => level.value == data.level_id)?.label ??
                                                    'Pilih Tingkat'}
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
                                    <Label htmlFor="classroom_id">Nama Kelas</Label>
                                    <Select
                                        defaultValue={data.classroom_id}
                                        onValueChange={(value) => setData('classroom_id', value)}
                                        id="classroom_id"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.classrooms.find(
                                                    (classroom) => classroom.value == data.classroom_id,
                                                )?.label ?? 'Pilih kelas'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.classrooms.map((classroom, index) => (
                                                    <SelectItem key={index} value={classroom.value}>
                                                        {classroom.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.classroom_id && <InputError message={errors.classroom_id} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="nisn">NISN</Label>
                                    <Input
                                        type="text"
                                        name="nisn"
                                        id="nisn"
                                        placeholder="Masukkan NISN"
                                        value={data.nisn}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.nisn && <InputError message={errors.nisn} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="batch">Angkatan</Label>
                                    <Input
                                        type="text"
                                        name="batch"
                                        id="batch"
                                        placeholder="Masukkan angkatan"
                                        value={data.batch}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.batch && <InputError message={errors.batch} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="gender">Jenis Kelamin</Label>
                                    <Select
                                        defaultValue={data.gender}
                                        onValueChange={(value) => setData('gender', value)}
                                        id="gender"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.genders.find((gender) => gender.value == data.gender)?.label ??
                                                    'Pilih Jenis Kelamin'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.genders.map((gender, index) => (
                                                    <SelectItem key={index} value={gender.value}>
                                                        {gender.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.gender && <InputError message={errors.gender} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="status">Status</Label>
                                    <Select
                                        defaultValue={data.status}
                                        onValueChange={(value) => setData('status', value)}
                                        id="status"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.statuses.find((status) => status.value == data.status)?.label ??
                                                    'Pilih Status'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.statuses.map((status, index) => (
                                                    <SelectItem key={index} value={status.value}>
                                                        {status.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.status && <InputError message={errors.status} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="address">Alamat</Label>
                                    <Textarea
                                        name="address"
                                        id="address"
                                        placeholder="Masukkan alamat lengkap siswa"
                                        value={data.address}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.address && <InputError message={errors.address} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="avatar">Avatar</Label>
                                    <Input
                                        type="file"
                                        accept="image/*"
                                        name="avatar"
                                        id="avatar"
                                        ref={fileInputAvatar}
                                        onChange={(e) => setData(e.target.name, e.target.files[0])}
                                    />
                                    {errors.avatar && <InputError message={errors.avatar} />}
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
