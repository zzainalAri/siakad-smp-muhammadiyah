import { Button } from '@/Components/ui/button';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from '@/Components/ui/sheet';
import { IconEye } from '@tabler/icons-react';

export default function AbsenStatistic({ students, classroom, course, meetingNumber }) {
    return (
        <Sheet>
            <SheetTrigger asChild>
                <Button variant="purple" size="sm">
                    <IconEye className="size-4 text-white" /> Lihat Statistik Absensi
                </Button>
            </SheetTrigger>
            <SheetContent side="top">
                <SheetHeader>
                    <SheetTitle>
                        Statistik Absensi Siswa Kelas {classroom} Pertemuan {meetingNumber}
                    </SheetTitle>
                    <SheetDescription>
                        Detail Absensi Siswa Kelas {classroom} Mata Pelajaran {course}
                    </SheetDescription>
                </SheetHeader>
                <dl className="-my-3 divide-y divide-gray-100 px-6 py-4 text-sm leading-6">
                    <div className="flex justify-between gap-x-4 py-3">
                        <dt className="text-foreground">Total Siswa Kelas {classroom}</dt>
                        <dd className="font-medium text-foreground">{students.length}</dd>
                    </div>
                    <div className="flex justify-between gap-x-4 py-3">
                        <dt className="text-foreground">Hadir</dt>
                        <dd className="font-medium text-foreground">
                            {students
                                .map((student) =>
                                    student.attendances.filter((attendance) => attendance.status == 'Hadir'),
                                )
                                .reduce((total, attendances) => total + attendances.length, 0)}
                        </dd>
                    </div>
                    <div className="flex justify-between gap-x-4 py-3">
                        <dt className="text-foreground">Izin</dt>
                        <dd className="font-medium text-foreground">
                            {students
                                .map((student) =>
                                    student.attendances.filter((attendance) => attendance.status == 'Izin'),
                                )
                                .reduce((total, attendances) => total + attendances.length, 0)}
                        </dd>
                    </div>
                    <div className="flex justify-between gap-x-4 py-3">
                        <dt className="text-foreground">Sakit</dt>
                        <dd className="font-medium text-foreground">
                            {students
                                .map((student) =>
                                    student.attendances.filter((attendance) => attendance.status == 'Sakit'),
                                )
                                .reduce((total, attendances) => total + attendances.length, 0)}
                        </dd>
                    </div>
                    <div className="flex justify-between gap-x-4 py-3">
                        <dt className="text-foreground">Alpha</dt>
                        <dd className="font-medium text-foreground">
                            {students
                                .map((student) =>
                                    student.attendances.filter((attendance) => attendance.status == 'Alpha'),
                                )
                                .reduce((total, attendances) => total + attendances.length, 0)}
                        </dd>
                    </div>
                    <div className="flex justify-between gap-x-4 py-3">
                        <dt className="text-foreground">Belum Absen</dt>
                        <dd className="font-medium text-foreground">
                            {students.filter((student) => student.attendances.length === 0).length == 0
                                ? 'Sudah Absen Semua'
                                : students.filter((student) => student.attendances.length === 0).length}
                        </dd>
                    </div>
                </dl>
            </SheetContent>
        </Sheet>
    );
}
