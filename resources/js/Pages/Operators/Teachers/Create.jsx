import HeaderTitle from '@/Components/HeaderTitle';
import InputError from '@/Components/InputError';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconCheck, IconUsersGroup } from '@tabler/icons-react';
import { useRef } from 'react';
import { toast } from 'sonner';

export default function Create(props) {
    const fileInputAvatar = useRef(null);

    const { data, setData, post, errors, processing, reset } = useForm({
        name: '',
        email: '',
        password: '',
        avatar: null,
        teacher_number: '',
        academic_title: '',
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
                        icon={IconUsersGroup}
                    />
                    <Button asChild variant="blue" size="xl" className="w-full lg:w-auto">
                        <Link href={route('operators.teachers.index')}>
                            <IconArrowLeft className="size-4" /> Kembali
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardContent className="p-6">
                        <form onSubmit={onHandleSubmit}>
                            <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
                                <div className="col-span-full">
                                    <Label htmlFor="name">Nama Dosen</Label>
                                    <Input
                                        type="text"
                                        name="name"
                                        id="name"
                                        placeholder="Masukkan nama dosen"
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
                                <div className="col-span-2">
                                    <Label htmlFor="teacher_number">Nomor Induk Dosen</Label>
                                    <Input
                                        type="text"
                                        name="teacher_number"
                                        id="teacher_number"
                                        placeholder="Masukkan nomor induk Dosen"
                                        value={data.teacher_number}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.teacher_number && <InputError message={errors.teacher_number} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="academic_title">Jabatan Akademik</Label>
                                    <Input
                                        type="text"
                                        name="academic_title"
                                        id="academic_title"
                                        placeholder="Masukkan Jabatan Akademik"
                                        value={data.academic_title}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.academic_title && <InputError message={errors.academic_title} />}
                                </div>
                                <div className="col-span-2">
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
