import CardStat from '@/Components/CardStat';
import HeaderTitle from '@/Components/HeaderTitle';
import AppLayout from '@/Layouts/AppLayout';
import hasAnyPermissions from '@/lib/utils';
import { usePage } from '@inertiajs/react';
import {
    IconBooks,
    IconBuildingSkyscraper,
    IconCalendar,
    IconDoor,
    IconLayout2,
    IconUsersGroup,
} from '@tabler/icons-react';

export default function Dashboard(props) {
    const auth = usePage().props.auth.user;
    const { permissions } = usePage().props.auth;
    console.log(props);
    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconLayout2}
                    />
                </div>
                <div className="mb-8 flex flex-col">
                    <h2 className="text-xl font-medium leading-relaxed text-foreground">Hi, {auth.name}</h2>
                    <p className="text-sm text-muted-foreground"> Selamat datang di Sistem Informasi Akademik</p>
                </div>
                <div className="mb-8 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    {hasAnyPermissions(permissions, ['dashboard.view']) && (
                        <>
                            <CardStat
                                data={{
                                    title: 'Tingkat',
                                    icon: IconBuildingSkyscraper,
                                    background: 'text-white bg-gradient-to-r from-red-400 via-red-500 to-red-500',
                                    iconClassName: 'text-white',
                                }}
                            >
                                <div className="text-2xl font-bold">{props.count.levels}</div>
                            </CardStat>
                            <CardStat
                                data={{
                                    title: 'Total Kelas',
                                    icon: IconDoor,
                                    background: 'text-white bg-gradient-to-r from-slate-400 via-slate-500 to-slate-500',
                                    iconClassName: 'text-white',
                                }}
                            >
                                <div className="text-2xl font-bold">{props.count.classrooms}</div>
                            </CardStat>
                            <CardStat
                                data={{
                                    title: 'Total Mata Pelajaran',
                                    icon: IconBooks,
                                    background:
                                        'text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-500',
                                    iconClassName: 'text-white',
                                }}
                            >
                                <div className="text-2xl font-bold">{props.count.courses}</div>
                            </CardStat>
                            <CardStat
                                data={{
                                    title: 'Total Guru',
                                    icon: IconUsersGroup,
                                    background: 'text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-500',
                                    iconClassName: 'text-white',
                                }}
                            >
                                <div className="text-2xl font-bold">{props.count.teachers}</div>
                            </CardStat>
                            <CardStat
                                data={{
                                    title: 'Total Siswa',
                                    icon: IconUsersGroup,
                                    background: 'text-white bg-gradient-to-r from-green-400 via-green-500 to-green-500',
                                    iconClassName: 'text-white',
                                }}
                            >
                                <div className="text-2xl font-bold">{props.count.students}</div>
                            </CardStat>
                        </>
                    )}
                    {hasAnyPermissions(permissions, ['dashboard.teachers.view']) && (
                        <>
                            <CardStat
                                data={{
                                    title: 'Total Mata Pelajaran yang Anda Ajar',
                                    icon: IconBooks,
                                    background: 'text-white bg-gradient-to-r from-red-400 via-red-500 to-red-500',
                                    iconClassName: 'text-white',
                                }}
                            >
                                <div className="text-2xl font-bold">{props.count.courses}</div>
                            </CardStat>
                            <CardStat
                                data={{
                                    title: 'Total Kelas yang Anda Ajar',
                                    icon: IconDoor,
                                    background: 'text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-500',
                                    iconClassName: 'text-white',
                                }}
                            >
                                <div className="text-2xl font-bold">{props.count.classrooms}</div>
                            </CardStat>
                            <CardStat
                                data={{
                                    title: 'Total Jadwal Anda',
                                    icon: IconCalendar,
                                    background: 'text-white bg-gradient-to-r from-slate-400 via-slate-500 to-slate-500',
                                    iconClassName: 'text-white',
                                }}
                            >
                                <div className="text-2xl font-bold">{props.count.schedules}</div>
                            </CardStat>
                        </>
                    )}
                </div>
            </div>
        </>
    );
}

Dashboard.layout = (page) => <AppLayout children={page} title={page.props.page_setting.title} />;
