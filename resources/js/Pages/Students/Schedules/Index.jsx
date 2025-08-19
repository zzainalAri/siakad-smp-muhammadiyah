import CalendarSchedule from '@/Components/CalendarSchedule';
import HeaderTitle from '@/Components/HeaderTitle';
import StudentLayout from '@/Layouts/StudentLayout';
import { groupSchedulesByDay } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/react';
import { IconCalendar } from '@tabler/icons-react';

export default function Index(props) {
    const schedules = props.scheduleTable;
    const days = props.days;
    const auth = usePage().props.auth.user;

    const mobile_schedules = groupSchedulesByDay(props.mobile_schedules);

    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 md:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconCalendar}
                    />
                </div>
                <div className="flex flex-col gap-y-8">
                    <CalendarSchedule days={days} schedules={schedules} student={auth.student} />
                    <div className="flex lg:hidden">
                        <div className="w-full space-y-4">
                            {days.map((day) => (
                                <Link href={'#'} key={day}>
                                    <h3 className="my-2 text-lg font-semibold">{day}</h3>
                                    <ul className="space-y-2">
                                        {(mobile_schedules[day] || []).map((schedule, index) => (
                                            <li key={index} className="rounded-md border p-3 shadow">
                                                <p className="font-medium">{schedule.course}</p>
                                                <p className="text-sm text-gray-600">
                                                    {schedule.start_time} - {schedule.end_time}
                                                </p>
                                                <p className="text-muted-foreground">{schedule.teacher}</p>
                                            </li>
                                        ))}
                                    </ul>
                                </Link>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

Index.layout = (page) => <StudentLayout children={page} title={page.props.page_setting.title} />;
