import NavLink from '@/Components/NavLink';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import hasAnyPermissions from '@/lib/utils';
import { Link } from '@inertiajs/react';
import {
    IconBooks,
    IconBuildingSkyscraper,
    IconCalendar,
    IconCalendarTime,
    IconCircleKey,
    IconClipboardPlus,
    IconDoor,
    IconLayout2,
    IconLogout2,
    IconMoneybag,
    IconReportMoney,
    IconUsers,
    IconUsersGroup,
    IconUsersPlus,
} from '@tabler/icons-react';

export default function SidebarResponsive({ url, auth }) {
    return (
        <nav className="mt-4 flex flex-1 flex-col">
            <ul className="flex flex-1 flex-col" role="list">
                <li className="-mx-6">
                    <Link
                        className="items-cemter flex gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-blue-800"
                        href={'#'}
                    >
                        <Avatar>
                            <AvatarImage src={auth.user.avatar} />
                            <AvatarFallback>{auth.user.name.substring(0, 1)}</AvatarFallback>
                        </Avatar>
                        <div className="flex flex-col text-left">
                            <span className="truncate font-bold">{auth.user.name}</span>
                            <span className="truncate">{auth.user.role_name}</span>
                        </div>
                    </Link>
                </li>
                {/* dashboard */}
                <NavLink
                    url={route('admin.dashboard')}
                    active={url.startsWith('/dashboard')}
                    title={'Dashboard'}
                    icon={IconLayout2}
                />

                {/* master */}
                {hasAnyPermissions(auth.permissions, [
                    'levels.index',
                    'academic-years.index',
                    'classrooms.index',
                    'roles.index',
                ]) && <div className="px-3 py-2 text-base font-medium text-white">Master</div>}
                {hasAnyPermissions(auth.permissions, ['levels.index']) && (
                    <NavLink
                        url={route('admin.levels.index')}
                        active={url.startsWith('/levels')}
                        title={'Tingkat'}
                        icon={IconBuildingSkyscraper}
                    />
                )}
                {hasAnyPermissions(auth.permissions, ['academic-years.index']) && (
                    <NavLink
                        url={route('admin.academic-years.index')}
                        active={url.startsWith('/academic-years')}
                        title={'Tahun Ajaran'}
                        icon={IconCalendarTime}
                    />
                )}

                {hasAnyPermissions(auth.permissions, ['classrooms.index']) && (
                    <NavLink
                        url={route('admin.classrooms.index')}
                        active={url.startsWith('/classrooms')}
                        title={'Kelas'}
                        icon={IconDoor}
                    />
                )}
                {hasAnyPermissions(auth.permissions, ['roles.index']) && (
                    <NavLink
                        url={route('admin.roles.index')}
                        active={url.startsWith('/roles')}
                        title={'Peran'}
                        icon={IconCircleKey}
                    />
                )}

                {/* Pengguna */}
                {hasAnyPermissions(auth.permissions, ['students.index', 'teachers.index']) && (
                    <div className="px-3 py-2 text-base font-medium text-white">Pengguna</div>
                )}

                {/* Pengguna */}
                {hasAnyPermissions(auth.permissions, ['students.index']) && (
                    <NavLink
                        url={route('admin.students.index')}
                        active={url.startsWith('/students')}
                        title={'Siswa'}
                        icon={IconUsers}
                    />
                )}
                {hasAnyPermissions(auth.permissions, ['teachers.index']) && (
                    <NavLink
                        url={route('admin.teachers.index')}
                        active={url.startsWith('/teachers')}
                        title={'Guru'}
                        icon={IconUsersGroup}
                    />
                )}
                {hasAnyPermissions(auth.permissions, ['users.index']) && (
                    <NavLink
                        url={route('admin.users.index')}
                        active={url.startsWith('/users')}
                        title={'Pengguna Lain'}
                        icon={IconUsersPlus}
                    />
                )}

                {/* Akademik */}
                {hasAnyPermissions(auth.permissions, [
                    'courses.index',
                    'schedules.index',
                    'teachers.courses.index',
                    'teachers.schedules.index',
                ]) && <div className="px-3 py-2 text-base font-medium text-white">Akademik</div>}
                {hasAnyPermissions(auth.permissions, ['courses.index']) && (
                    <NavLink
                        url={route('admin.courses.index')}
                        active={url.startsWith('/courses')}
                        title={'Kelola Mata Pelajaran'}
                        icon={IconBooks}
                    />
                )}
                {hasAnyPermissions(auth.permissions, ['schedules.index']) && (
                    <NavLink
                        url={route('admin.schedules.index')}
                        active={url.startsWith('/schedules')}
                        title={'Kelola Jadwal'}
                        icon={IconCalendar}
                    />
                )}

                {/* teacher */}
                {hasAnyPermissions(auth.permissions, ['teachers.courses.index']) && (
                    <NavLink
                        url={route('teachers.courses.index')}
                        active={url.startsWith('/teachers/courses')}
                        title={'Mata Pelajaran Anda'}
                        icon={IconBooks}
                    />
                )}
                {hasAnyPermissions(auth.permissions, ['teachers.schedules.index']) && (
                    <NavLink
                        url={route('teachers.schedules.index')}
                        active={url.startsWith('/teachers/schedules')}
                        title={'Jadwal Anda'}
                        icon={IconCalendarTime}
                    />
                )}

                {/* Pembayaran */}
                {hasAnyPermissions(auth.permissions, ['fees.index', 'fee-groups.index']) && (
                    <div className="px-3 py-2 text-base font-medium text-white">Pembayaran</div>
                )}
                {hasAnyPermissions(auth.permissions, ['fees.index']) && (
                    <NavLink
                        url={route('admin.fees.index')}
                        active={url.startsWith('/fees')}
                        title={'SPP'}
                        icon={IconReportMoney}
                    />
                )}
                {hasAnyPermissions(auth.permissions, ['fee-groups.index']) && (
                    <NavLink
                        url={route('admin.fee-groups.index')}
                        active={url.startsWith('/fee-groups')}
                        title={'Pengaturan SPP'}
                        icon={IconMoneybag}
                    />
                )}

                {/* Lainnya */}
                <div className="px-3 py-1 text-base font-medium text-white">Lainnya</div>
                {hasAnyPermissions(auth.permissions, ['ppdb.index']) && (
                    <NavLink
                        url={route('admin.student-registrations.index')}
                        active={url.startsWith('/student-registrations')}
                        title={'PPDB'}
                        icon={IconClipboardPlus}
                    />
                )}
                <NavLink
                    url={route('logout')}
                    method="post"
                    as="button"
                    active={url.startsWith('/logout')}
                    title={'Logout'}
                    className="w-full"
                    icon={IconLogout2}
                />
            </ul>
        </nav>
    );
}
