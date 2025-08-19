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
    const fileInputAkta = useRef(null);
    const fileInputKk = useRef(null);

    const { data, setData, post, errors, processing, reset } = useForm({
        name: '',
        email: '',
        nisn: '',
        birth_place: '',
        birth_date: null,
        address: '',
        phone: '',
        previous_school: '',
        gender: null,
        religion: 'null',
        mother_name: '',
        mother_nik: '',
        father_name: '',
        father_nik: '',
        no_kk: '',
        nik: '',
        accpepted_date: null,
        _method: props.page_setting.method,
    });

    const onHandleReset = () => {
        reset();
        fileInputAkta.current.value = null;
        fileInputKk.current.value = null;
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
                        <Link href={route('admin.student-registrations.index')}>
                            <IconArrowLeft className="size-4" /> Kembali
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardContent className="p-6">
                        <form onSubmit={onHandleSubmit}>
                            <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                                <div className="col-span-2">
                                    <Label htmlFor="name">Nama Calon Siswa</Label>
                                    <Input
                                        type="text"
                                        name="name"
                                        id="name"
                                        placeholder="Masukkan nama calon Siswa"
                                        value={data.name}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.name && <InputError message={errors.name} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="email">Email Calon Siswa</Label>
                                    <Input
                                        type="text"
                                        name="email"
                                        id="email"
                                        placeholder="Masukkan nama calon Siswa"
                                        value={data.email}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.email && <InputError message={errors.email} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="nisn">Nisn</Label>
                                    <Input
                                        type="text"
                                        name="nisn"
                                        id="nisn"
                                        placeholder="Masukkan nisn"
                                        value={data.nisn}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.nisn && <InputError message={errors.nisn} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="nik">NIK Calon Siswa</Label>
                                    <Input
                                        type="text"
                                        name="nik"
                                        id="nik"
                                        placeholder="Masukkan nik"
                                        value={data.nik}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.nik && <InputError message={errors.nik} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="no_kk">Nomer Kartu Keluarga</Label>
                                    <Input
                                        type="text"
                                        name="no_kk"
                                        id="no_kk"
                                        placeholder="Masukkan no kk"
                                        value={data.no_kk}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.no_kk && <InputError message={errors.no_kk} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="religion">Agama</Label>
                                    <Select
                                        defaultValue={data.religion}
                                        onValueChange={(value) => setData('religion', value)}
                                        id="religion"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.religions.find((religion) => religion.value == data.religion)
                                                    ?.label ?? 'Pilih Agama'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.religions.map((religion, index) => (
                                                    <SelectItem key={index} value={religion.value}>
                                                        {religion.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.religion && <InputError message={errors.religion} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="previous_school">Nama Sekolah Dasar Sebelumnya</Label>
                                    <Input
                                        type="text"
                                        name="previous_school"
                                        id="previous_school"
                                        placeholder="Masukkan Nama Sekolah Dasar Sebelumnya"
                                        value={data.previous_school}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.previous_school && <InputError message={errors.previous_school} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="birth_place">Tempat Lahir</Label>
                                    <Input
                                        type="text"
                                        name="birth_place"
                                        id="birth_place"
                                        placeholder="Cth: Bandung"
                                        value={data.birth_place}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.birth_place && <InputError message={errors.birth_place} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="birth_date">Tanggal Lahir</Label>
                                    <Input
                                        type="date"
                                        name="birth_date"
                                        id="birth_date"
                                        placeholder="Cth: Bandung"
                                        value={data.birth_date}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.birth_date && <InputError message={errors.birth_date} />}
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
                                <div className="col-span-2">
                                    <Label htmlFor="phone">No Hp</Label>
                                    <Input
                                        type="text"
                                        name="phone"
                                        id="phone"
                                        placeholder="Cth: Bandung"
                                        value={data.phone}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.phone && <InputError message={errors.phone} />}
                                </div>
                                <div className="col-span-2">
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
                                <div className="col-span-2">
                                    <Label htmlFor="mother_name">Nama Ibu</Label>
                                    <Input
                                        type="text"
                                        name="mother_name"
                                        id="mother_name"
                                        placeholder="Masukkan Nama Ibu"
                                        value={data.mother_name}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.mother_name && <InputError message={errors.mother_name} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="mother_nik">NIK Ibu</Label>
                                    <Input
                                        type="text"
                                        name="mother_nik"
                                        id="mother_nik"
                                        placeholder="Masukkan nik ibu"
                                        value={data.mother_nik}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.mother_nik && <InputError message={errors.mother_nik} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="father_name">Nama Ayah</Label>
                                    <Input
                                        type="text"
                                        name="father_name"
                                        id="father_name"
                                        placeholder="Masukkan Nama Ibu"
                                        value={data.father_name}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.father_name && <InputError message={errors.father_name} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="father_nik">NIK Ayah</Label>
                                    <Input
                                        type="text"
                                        name="father_nik"
                                        id="father_nik"
                                        placeholder="Masukkan nik ibu"
                                        value={data.father_nik}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.father_nik && <InputError message={errors.father_nik} />}
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
