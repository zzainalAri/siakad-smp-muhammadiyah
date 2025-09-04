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
import { IconArrowLeft, IconCalendar, IconCheck } from '@tabler/icons-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

export default function Create(props) {
    const { data, setData, post, errors, processing, reset } = useForm({
        level_id: null,
        course_id: null,
        classroom_id: null,
        start_time: '',
        end_time: '',
        day_of_week: null,
        _method: props.page_setting.method,
    });
    const [filteredCourse, setFilteredCourse] = useState([]);
    const [filteredClassroom, setFilteredClassroom] = useState([]);
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

    useEffect(() => {
        const filteredCourse = props.courses.filter((course) => course.level_id == data.level_id);
        const filteredClassroom = props.classrooms.filter((classroom) => classroom.level_id == data.level_id);
        setFilteredCourse(filteredCourse);
        setFilteredClassroom(filteredClassroom);
    }, [data.level_id]);

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
                        <Link href={route('admin.schedules.index')}>
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
                                    <Label htmlFor="course_id">Mata Pelajaran</Label>
                                    <Select
                                        defaultValue={data.course_id}
                                        onValueChange={(value) => setData('course_id', value)}
                                        id="course_id"
                                        disabled={!data.level_id}
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {filteredCourse.find((course) => course.value == data.course_id)
                                                    ?.label ?? 'Pilih Tingkat Dahulu'}
                                            </SelectValue>
                                            <SelectContent>
                                                {filteredCourse.map((course, index) => (
                                                    <SelectItem key={index} value={course.value}>
                                                        {course.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.course_id && <InputError message={errors.course_id} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="classroom_id">Kelas</Label>
                                    <Select
                                        defaultValue={data.classroom_id}
                                        onValueChange={(value) => setData('classroom_id', value)}
                                        id="classroom_id"
                                        disabled={!data.level_id}
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {filteredClassroom.find(
                                                    (classroom) => classroom.value == data.classroom_id,
                                                )?.label ?? 'Pilih Tingkat Dahulu'}
                                            </SelectValue>
                                            <SelectContent>
                                                {filteredClassroom.map((classroom, index) => (
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
                                    <Label htmlFor="start_time">Masukan Waktu Mulai</Label>
                                    <Input
                                        type="time"
                                        name="start_time"
                                        id="start_time"
                                        placeholder="Masukkan waktu mulai"
                                        value={data.start_time}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.start_time && <InputError message={errors.start_time} />}
                                </div>
                                <div className="col-span-2">
                                    <Label htmlFor="end_time">Masukan Waktu Berakhir</Label>
                                    <Input
                                        type="time"
                                        name="end_time"
                                        id="end_time"
                                        placeholder="Masukkan waktu mulai"
                                        value={data.end_time}
                                        onChange={(e) => setData(e.target.name, e.target.value)}
                                    />
                                    {errors.end_time && <InputError message={errors.end_time} />}
                                </div>
                                <div className="col-span-full">
                                    <Label htmlFor="day_of_week">Hari</Label>
                                    <Select
                                        defaultValue={data.day_of_week}
                                        onValueChange={(value) => setData('day_of_week', value)}
                                        id="day_of_week"
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.days.find((day) => day.value == data.day_of_week)?.label ??
                                                    'Pilih Hari'}
                                            </SelectValue>
                                            <SelectContent>
                                                {props.days.map((day, index) => (
                                                    <SelectItem key={index} value={day.value}>
                                                        {day.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </SelectTrigger>
                                    </Select>
                                    {errors.day_of_week && <InputError message={errors.day_of_week} />}
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
