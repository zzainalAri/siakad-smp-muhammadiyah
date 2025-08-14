import HeaderTitle from '@/Components/HeaderTitle';
import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import { Label } from '@/Components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import StudentLayout from '@/Layouts/StudentLayout';
import { cn, flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowBack, IconBuilding, IconCheck } from '@tabler/icons-react';
import { toast } from 'sonner';

export default function Create(props) {
    const { data, setData, errors, post, processing, reset } = useForm({
        schedule_id: [],
        _method: props.page_setting.method,
    });

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

    const onHandleReset = () => {
        reset();
    };
    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconBuilding}
                    />
                    <Button asChild variant="blue" size="xl" className="w-full lg:w-auto">
                        <Link href={route('students.study-plans.index')}>
                            <IconArrowBack className="size-4" /> Kembali
                        </Link>
                    </Button>
                </div>
                <form onSubmit={onHandleSubmit}>
                    <Table className="w-full">
                        <TableHeader>
                            <TableRow>
                                <TableHead>#</TableHead>
                                <TableHead>Mata Pelajaran</TableHead>
                                <TableHead>Kelas</TableHead>
                                <TableHead>Hari</TableHead>
                                <TableHead>Jam</TableHead>
                                <TableHead>Kuota</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {props.schedules.map((schedule, index) => (
                                <TableRow
                                    key={index}
                                    className={cn(
                                        schedule.taken_quota === schedule.quote &&
                                            'text-red-500 hover:cursor-not-allowed',
                                    )}
                                >
                                    <TableCell>
                                        <Checkbox
                                            id={`schedule_id_${schedule.id}`}
                                            name="schedule_id"
                                            checked={data.schedule_id.includes(schedule.id)}
                                            disabled={schedule.taken_quota == schedule.quote}
                                            onCheckedChange={(checked) => {
                                                if (checked) {
                                                    setData('schedule_id', [...data.schedule_id, schedule.id]);
                                                } else {
                                                    setData(
                                                        'schedule_id',
                                                        data.schedule_id.filter((id) => id !== schedule.id),
                                                    );
                                                }
                                            }}
                                        />
                                    </TableCell>
                                    <TableCell>
                                        <Label
                                            className={
                                                schedule.taken_quota === schedule.quote
                                                    ? 'text-red-500 hover:cursor-not-allowed'
                                                    : 'hover:cursor-pointer'
                                            }
                                            htmlFor={`schedule_id_${schedule.id}`}
                                        >
                                            {schedule.course.name}
                                        </Label>
                                    </TableCell>
                                    <TableCell>{schedule.classroom.name}</TableCell>
                                    <TableCell>{schedule.day_of_week}</TableCell>
                                    <TableCell>
                                        {schedule.start_time} - {schedule.end_time}
                                    </TableCell>
                                    <TableCell
                                        className={cn(
                                            schedule.taken_quota === schedule.quote ? 'text-red-500' : 'text-green-500',
                                        )}
                                    >
                                        {schedule.taken_quota} / {schedule.quote}
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
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
            </div>
        </>
    );
}

Create.layout = (page) => <StudentLayout children={page} title={page.props.page_setting.title} />;
