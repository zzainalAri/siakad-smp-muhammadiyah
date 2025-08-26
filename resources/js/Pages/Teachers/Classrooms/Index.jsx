import AbsenStatistic from '@/Components/AbsenStatistic';
import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import ShowFilter from '@/Components/ShowFilter';
import { Alert, AlertDescription } from '@/Components/ui/alert';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Input } from '@/Components/ui/input';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs';
import UseFilter from '@/hooks/UseFilter';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage, formatDateIndo } from '@/lib/utils';
import { useForm } from '@inertiajs/react';
import { IconCheck, IconDoor, IconDotsVertical, IconRefresh } from '@tabler/icons-react';
import { useState } from 'react';
import { toast } from 'sonner';

export default function Index(props) {
    const { students, sections, attendanceStatuses } = props;
    const [params, setParams] = useState(props.state);
    const today = new Date().toISOString().split('T')[0];

    const { data, setData, errors, processing, post, reset } = useForm({
        attendances: [],
        grades: [],
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
                reset();
            },
        });
    };

    const getGradeStudent = (student_id, grades, category) => {
        return grades.find((grade) => grade.student_id === student_id && grade.category === category);
    };

    UseFilter({
        route: route('teachers.classrooms.index', [props.course, props.classroom]),
        values: params,
        only: ['students'],
    });

    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconDoor}
                    />
                </div>

                <Card>
                    <CardHeader className="mb-4 p-0">
                        <div className="flex w-full flex-col gap-4 px-6 py-4 lg:flex-row lg:items-center">
                            <Input
                                className="w-full sm:w-1/4"
                                placeholder="Cari nama Siswa"
                                value={params?.search}
                                onChange={(e) => setParams((prev) => ({ ...prev, search: e.target.value }))}
                            />

                            <Button variant="red" size="xl" onClick={(e) => setParams(props.state)}>
                                <IconRefresh className="size-4" />
                                Bersihkan
                            </Button>
                        </div>
                        <div className="space-y-4 px-6">
                            <Alert variant="destructive">
                                <AlertDescription>
                                    Harap isi dengan teliti, data yang sudah disimpan tidak dapat diperbarui
                                </AlertDescription>
                            </Alert>
                            {errors && Object.keys(errors).length > 0 && (
                                <Alert variant="desctructive">
                                    <AlertDescription>
                                        {typeof errors === 'string' ? (
                                            errors
                                        ) : (
                                            <ul>
                                                {Object.entries(errors).map(([key, message]) => (
                                                    <li key={key}>{message}</li>
                                                ))}
                                            </ul>
                                        )}
                                    </AlertDescription>
                                </Alert>
                            )}
                        </div>
                        <ShowFilter params={params} />
                    </CardHeader>
                    <CardContent className="[&-td]: p-0 [&-td]:whitespace-nowrap [&-th]:px-6">
                        <Tabs className="mx-4" defaultValue={sections[0]?.id.toString() || '0'}>
                            <TabsList className="scroll-bar flex h-fit w-full flex-wrap items-center justify-start gap-2 overflow-x-auto md:w-fit">
                                {sections.map((section) => (
                                    <TabsTrigger
                                        key={section.id}
                                        value={section.id.toString()}
                                        onClick={() => {
                                            setParams((prev) => ({ ...prev, meetingNumber: section.meeting_number }));
                                            setData('attendances', []);
                                            setData('grades', []);
                                        }}
                                    >
                                        Pertemuan {section.meeting_number}
                                    </TabsTrigger>
                                ))}
                            </TabsList>

                            {sections.map((section) => {
                                const isLocked = section.meeting_date > today;
                                const studentsInSection = students.map((student) => {
                                    const attendance = student.attendances.find((att) => att.section_id === section.id);
                                    return { ...student, sectionAttendance: attendance };
                                });

                                return (
                                    <TabsContent key={section.id} value={section.id.toString()}>
                                        {isLocked ? (
                                            <EmptyState
                                                title="Belum bisa diakses"
                                                subtitle={`Pertemuan ini akan dibuka pada ${formatDateIndo(section.meeting_date)}`}
                                                icon={IconDoor}
                                            />
                                        ) : (
                                            <>
                                                <form onSubmit={onHandleSubmit}>
                                                    <Table className="w-full border">
                                                        <TableHeader>
                                                            <TableRow>
                                                                <TableHead rowSpan="2">#</TableHead>
                                                                <TableHead rowSpan="2">Nama</TableHead>
                                                                <TableHead rowSpan="2">Nomor Induk Siswa</TableHead>
                                                                <TableHead colSpan="4" className="border">
                                                                    <div className="flex items-center justify-between">
                                                                        <p>Absensi</p>
                                                                        <DropdownMenu>
                                                                            <DropdownMenuTrigger asChild>
                                                                                <Button variant="ghost">
                                                                                    <IconDotsVertical className="size-4" />
                                                                                </Button>
                                                                            </DropdownMenuTrigger>
                                                                            <DropdownMenuContent className="w-fit">
                                                                                <DropdownMenuGroup>
                                                                                    <DropdownMenuItem asChild>
                                                                                        <AbsenStatistic
                                                                                            students={students}
                                                                                            classroom={
                                                                                                props.classroom.name
                                                                                            }
                                                                                            meetingNumber={
                                                                                                section.meeting_number
                                                                                            }
                                                                                            course={props.course.name}
                                                                                        />
                                                                                    </DropdownMenuItem>
                                                                                </DropdownMenuGroup>
                                                                            </DropdownMenuContent>
                                                                        </DropdownMenu>
                                                                    </div>
                                                                </TableHead>

                                                                <TableHead rowSpan="2" className="border text-center">
                                                                    Nilai Tugas Pertemuan {section.meeting_number}
                                                                </TableHead>
                                                            </TableRow>
                                                            <TableRow rowSpan="4" className="border">
                                                                {props.attendanceStatuses.map((item, i) => (
                                                                    <TableHead key={i} className="border">
                                                                        {item.value}
                                                                    </TableHead>
                                                                ))}
                                                            </TableRow>
                                                        </TableHeader>
                                                        <TableBody>
                                                            {studentsInSection.map((student, index) => (
                                                                <TableRow key={index}>
                                                                    <TableCell>{index + 1}</TableCell>
                                                                    <TableCell>
                                                                        <div className="flex items-center gap-2">
                                                                            <Avatar>
                                                                                <AvatarImage
                                                                                    src={student.user.avatar}
                                                                                />
                                                                                <AvatarFallback>
                                                                                    {student.user.name.substring(0, 1)}
                                                                                </AvatarFallback>
                                                                            </Avatar>
                                                                            <span>{student.user.name}</span>
                                                                        </div>
                                                                    </TableCell>
                                                                    <TableCell>{student.nisn}</TableCell>
                                                                    {attendanceStatuses.map((attendance) => {
                                                                        const currentStatus = data.attendances.find(
                                                                            (att) => att.student_id === student.id,
                                                                        )?.status;

                                                                        const attendanced = (
                                                                            student.attendances || []
                                                                        ).find(
                                                                            (att) => att.status === attendance.value,
                                                                        );

                                                                        return (
                                                                            <TableCell
                                                                                key={attendance.value}
                                                                                className="border text-center"
                                                                            >
                                                                                {(student.attendances || []).length ===
                                                                                0 ? (
                                                                                    <input
                                                                                        type="checkbox"
                                                                                        checked={
                                                                                            currentStatus ===
                                                                                            attendance.value
                                                                                        }
                                                                                        onChange={() => {
                                                                                            const updated =
                                                                                                data.attendances.filter(
                                                                                                    (att) =>
                                                                                                        att.student_id !==
                                                                                                        student.id,
                                                                                                );

                                                                                            if (
                                                                                                currentStatus !==
                                                                                                attendance.value
                                                                                            ) {
                                                                                                updated.push({
                                                                                                    student_id:
                                                                                                        student.id,
                                                                                                    status: attendance.value,
                                                                                                    section_id:
                                                                                                        section.id,
                                                                                                });
                                                                                            }

                                                                                            setData(
                                                                                                'attendances',
                                                                                                updated,
                                                                                            );
                                                                                        }}
                                                                                    />
                                                                                ) : (
                                                                                    attendanced && (
                                                                                        <IconCheck className="size-4 text-green-500" />
                                                                                    )
                                                                                )}
                                                                            </TableCell>
                                                                        );
                                                                    })}

                                                                    <TableCell colSpan="2" className="text-center">
                                                                        {getGradeStudent(
                                                                            student.id,
                                                                            student.grades,
                                                                            'task',
                                                                        ) ? (
                                                                            getGradeStudent(
                                                                                student.id,
                                                                                student.grades,
                                                                                'task',
                                                                            ).grade
                                                                        ) : (
                                                                            <Input
                                                                                type="number"
                                                                                className="mx-auto w-[120px]"
                                                                                value={
                                                                                    data.grades.find(
                                                                                        (g) =>
                                                                                            g.student_id ===
                                                                                                student.id &&
                                                                                            g.category === 'task',
                                                                                    )?.grade || ''
                                                                                }
                                                                                onChange={(e) => {
                                                                                    const updatedGrades =
                                                                                        data.grades.filter(
                                                                                            (g) =>
                                                                                                !(
                                                                                                    g.student_id ===
                                                                                                        student.id &&
                                                                                                    g.category ===
                                                                                                        'task'
                                                                                                ),
                                                                                        );
                                                                                    updatedGrades.push({
                                                                                        student_id: student.id,
                                                                                        course_id: props.course.id,
                                                                                        section_id: section.id,
                                                                                        category: 'task',
                                                                                        grade:
                                                                                            parseFloat(
                                                                                                e.target.value,
                                                                                            ) || 0,
                                                                                    });
                                                                                    setData('grades', updatedGrades);
                                                                                }}
                                                                            />
                                                                        )}
                                                                    </TableCell>
                                                                </TableRow>
                                                            ))}
                                                        </TableBody>
                                                        <TableFooter>
                                                            <TableRow>
                                                                <TableCell colSpan="37">
                                                                    <Button
                                                                        variant="blue"
                                                                        type="submit"
                                                                        size="lg"
                                                                        disabled={processing}
                                                                    >
                                                                        <IconCheck />
                                                                        Simpan
                                                                    </Button>
                                                                </TableCell>
                                                            </TableRow>
                                                        </TableFooter>
                                                    </Table>
                                                </form>
                                            </>
                                        )}
                                    </TabsContent>
                                );
                            })}
                        </Tabs>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} title={page.props.page_setting.title} />;
