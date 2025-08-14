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
        name: props.student.name ?? '',
        nisn: props.student.nisn ?? '',
        birth_place: props.student.birth_place ?? '',
        birth_date: props.student.birth_date ?? null,
        address: props.student.address ?? '',
        phone: props.student.phone ?? '',
        previous_school: props.student.previous_school ?? '',
        gender: props.student.gender ?? null,
        nik: props.student.nik ?? '',
        status: props.student.status ?? null,
        doc_kk: null,
        doc_akta: null,
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
                                <div className="col-span-full">
                                    <Label htmlFor="nik">NIK</Label>
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
                                <div className="col-span-2">
                                    <Label htmlFor="doc_kk">Kartu Keluarga</Label>
                                    <Input
                                        type="file"
                                        accept="image/*"
                                        name="doc_kk"
                                        id="doc_kk"
                                        ref={fileInputKk}
                                        onChange={(e) => setData(e.target.name, e.target.files[0])}
                                    />
                                    <p className="my-1 text-xs text-muted-foreground">
                                        Pastikan foto kartu keluarga terlihat jelas. hanya mendukung jpg,jpeg, dan png
                                    </p>
                                    {errors.doc_kk && <InputError message={errors.doc_kk} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="doc_kk">Akta Kelahiran</Label>
                                    <Input
                                        type="file"
                                        accept="image/*"
                                        name="doc_akta"
                                        id="doc_akta"
                                        ref={fileInputAkta}
                                        onChange={(e) => setData(e.target.name, e.target.files[0])}
                                    />
                                    <p className="my-1 text-xs text-muted-foreground">
                                        Pastikan foto akta Kelahiran terlihat jelas. hanya mendukung jpg,jpeg, dan png
                                    </p>
                                    {errors.doc_akta && <InputError message={errors.doc_akta} />}
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
