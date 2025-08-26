import EmptyState from '@/Components/EmptyState';
import Fees from '@/Components/Fees';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import StudentLayout from '@/Layouts/StudentLayout';
import { feeCodeGenerator, FEESTATUSVARIANT, formatDateIndo, formatToRupiah } from '@/lib/utils';
import { router, usePage } from '@inertiajs/react';
import { IconArrowsDownUp, IconMoneybag, IconRefresh } from '@tabler/icons-react';
import axios from 'axios';
import { useState } from 'react';
import { toast } from 'sonner';

export default function Index(props) {
    const { data: payments, meta, links } = props.payments;
    const [open, setOpen] = useState(false);
    const [loading, setLoading] = useState(false);
    const { students } = props;
    const [params, setParams] = useState(props.state);

    const auth = usePage().props.auth.user;

    const handlePayment = async ({ fee_id, amount }) => {
        setLoading(true);
        try {
            const response = await axios.post(route('payments.create'), {
                order_id: feeCodeGenerator(),
                gross_amount: amount,
                first_name: auth.name,
                last_name: '',
                email: auth.email,
                fee_id,
                student_id: auth.student.id,
            });

            const snapToken = response.data.snapToken;

            window.snap.pay(snapToken, {
                onSuccess: function (result) {
                    toast['success']('Pembayaran Success');
                    router.get(route('payments.success'));
                },
                onPending: function (result) {
                    toast['warning']('Pembayaran Pending');
                    router.get(route('students.fees.index'));
                },
                onError: function (error) {
                    toast['error'](`Kesalahan Pembayaran ${error}`);
                    router.get(route('students.fees.index'));
                },
                onClose: function () {
                    toast['info']('Pembayaran ditutup');
                    router.get(route('students.fees.index'));
                },
            });
        } catch (error) {
            toast['error'](`Kesalahan pembayaran ${error}`);
        } finally {
            setLoading(false);
            setOpen(false);
        }
    };

    const onSortable = (field) => {
        setParams({
            ...params,
            field: field,
            direction: params.direction === 'asc' ? 'desc' : 'asc',
        });
    };
    UseFilter({
        route: route('students.fees.index'),
        values: params,
        only: ['payments'],
    });
    return (
        <>
            <div className="flex w-full flex-col pb-32">
                <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                    <HeaderTitle
                        title={props.page_setting.title}
                        subtitle={props.page_setting.subtitle}
                        icon={IconMoneybag}
                    />
                </div>
                <div className="flex flex-col gap-y-8">
                    {/* Pembayaran */}
                    {!props.checkFee && (
                        <div>
                            <Alert variant="slate">
                                <AlertTitle>Periode Pembayaran SPP Tahun Ajaran {props.academic_year.name}</AlertTitle>
                                <AlertDescription>
                                    Silakan melakukan pembayaran terlebih dahulu agar Anda dapat melanjutkan aktivitas
                                    akademik.
                                </AlertDescription>
                            </Alert>
                        </div>
                    )}
                    <Card>
                        <CardHeader className="font-bold">Tagihan</CardHeader>
                        <CardContent className="">
                            <div>
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>
                                                <Button
                                                    variant="ghost"
                                                    className="group inline-flex"
                                                    onClick={() => onSortable('name')}
                                                >
                                                    Nama
                                                    <span className="ml-2 flex-none rounded text-muted-foreground">
                                                        <IconArrowsDownUp className="size-4" />
                                                    </span>
                                                </Button>
                                            </TableHead>
                                            <TableHead>
                                                <Button
                                                    variant="ghost"
                                                    className="group inline-flex"
                                                    onClick={() => onSortable('classroom_id')}
                                                >
                                                    Kelas
                                                    <span className="ml-2 flex-none rounded text-muted-foreground">
                                                        <IconArrowsDownUp className="size-4" />
                                                    </span>
                                                </Button>
                                            </TableHead>
                                            <TableHead>Total Sudah Dibayar</TableHead>
                                            <TableHead>Total Belum Dibayar</TableHead>
                                            <TableHead>Total Tagihan Keseluruhan</TableHead>
                                            <TableHead>Aksi</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {students.map((student, index) => (
                                            <TableRow key={index}>
                                                <TableCell className="flex items-center gap-2">
                                                    <Avatar>
                                                        <AvatarImage src={student.user?.avatar} />
                                                        <AvatarFallback>
                                                            {student.user?.name.substring(0, 1)}
                                                        </AvatarFallback>
                                                    </Avatar>
                                                    <span>{student.user?.name}</span>
                                                </TableCell>
                                                <TableCell>{student.classroom?.name}</TableCell>
                                                <TableCell>{formatToRupiah(student.paid_fees_sum)}</TableCell>
                                                <TableCell>{formatToRupiah(student.unpaid_fees_sum)}</TableCell>
                                                <TableCell>{formatToRupiah(student.total_fees)}</TableCell>

                                                <TableCell>
                                                    <div className="flex items-center gap-x-1">
                                                        <Fees
                                                            loading={loading}
                                                            open={open}
                                                            setOpen={setOpen}
                                                            fees={student.fees}
                                                            name={student.user?.name}
                                                            total_fees={student.total_fees}
                                                            paid_fees_sum={student.paid_fees_sum}
                                                            unpaid_fees_sum={student.unpaid_fees_sum}
                                                            handlePayment={handlePayment}
                                                        />
                                                    </div>
                                                </TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="font-bold">Riwayat Pembayaran</CardHeader>
                        <CardContent>
                            <div className="flex w-full flex-col gap-4 lg:flex-row lg:items-center">
                                <Input
                                    className="w-full sm:w-1/4"
                                    placeholder="search"
                                    value={params?.search}
                                    onChange={(e) => setParams((prev) => ({ ...prev, search: e.target.value }))}
                                />
                                <Select value={params?.load} onValueChange={(e) => setParams({ ...params, load: e })}>
                                    <SelectTrigger className="w-full sm:w-24">
                                        <SelectValue placeholder="Load" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {[10, 23, 50, 75, 100].map((number, index) => (
                                            <SelectItem key={index} value={number}>
                                                {number}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <Button variant="red" onClick={() => setParams(props.state)} size="xl">
                                    <IconRefresh className="size-4" />
                                    Bersihkan
                                </Button>
                            </div>
                            {/* show filter */}
                            <div className="my-5 pb-1">
                                <ShowFilter className="my-5" params={params} />
                            </div>
                            {payments.length === 0 ? (
                                <EmptyState icon={IconMoneybag} title="Tidak ada riwayat pembayaran" />
                            ) : (
                                <Table className="w-full">
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>
                                                <Button
                                                    variant="ghost"
                                                    className="group inline-flex"
                                                    onClick={() => onSortable('id')}
                                                >
                                                    #
                                                    <span className="ml-2 flex-none rounded text-muted-foreground">
                                                        <IconArrowsDownUp className="size-4" />
                                                    </span>
                                                </Button>
                                            </TableHead>

                                            <TableHead>
                                                <Button
                                                    variant="ghost"
                                                    className="group inline-flex"
                                                    onClick={() => onSortable('fee_code')}
                                                >
                                                    No Pembayaran
                                                    <span className="ml-2 flex-none rounded text-muted-foreground">
                                                        <IconArrowsDownUp className="size-4" />
                                                    </span>
                                                </Button>
                                            </TableHead>
                                            <TableHead>
                                                <Button
                                                    variant="ghost"
                                                    className="group inline-flex"
                                                    onClick={() => onSortable('transaction_code')}
                                                >
                                                    Kode Pembayaran
                                                    <span className="ml-2 flex-none rounded text-muted-foreground">
                                                        <IconArrowsDownUp className="size-4" />
                                                    </span>
                                                </Button>
                                            </TableHead>
                                            <TableHead>Tahun Ajaran</TableHead>
                                            <TableHead>Semester</TableHead>
                                            <TableHead>Jumlah yang Dibayar</TableHead>
                                            <TableHead>
                                                <Button
                                                    variant="ghost"
                                                    className="group inline-flex"
                                                    onClick={() => onSortable('status')}
                                                >
                                                    Status Pembayaran
                                                    <span className="ml-2 flex-none rounded text-muted-foreground">
                                                        <IconArrowsDownUp className="size-4" />
                                                    </span>
                                                </Button>
                                            </TableHead>
                                            <TableHead>
                                                <Button
                                                    variant="ghost"
                                                    className="group inline-flex"
                                                    onClick={() => onSortable('payment_date')}
                                                >
                                                    Dibayar Pada
                                                    <span className="ml-2 flex-none rounded text-muted-foreground">
                                                        <IconArrowsDownUp className="size-4" />
                                                    </span>
                                                </Button>
                                            </TableHead>
                                            <TableHead>
                                                <Button
                                                    variant="ghost"
                                                    className="group inline-flex"
                                                    onClick={() => onSortable('created_at')}
                                                >
                                                    Dibuat Pada
                                                    <span className="ml-2 flex-none rounded text-muted-foreground">
                                                        <IconArrowsDownUp className="size-4" />
                                                    </span>
                                                </Button>
                                            </TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {payments.map((payment, index) => (
                                            <TableRow key={index}>
                                                <TableCell>
                                                    {index + 1 + (meta.current_page - 1) * meta.per_page}
                                                </TableCell>
                                                <TableCell>{payment.fee.fee_code}</TableCell>
                                                <TableCell>{payment.transaction_code}</TableCell>
                                                <TableCell>{payment.fee.academic_year}</TableCell>
                                                <TableCell>{payment.fee.semester}</TableCell>
                                                <TableCell>{formatToRupiah(payment.amount_paid)}</TableCell>
                                                <TableCell>
                                                    <Badge variant={FEESTATUSVARIANT[payment.status]}>
                                                        {payment.status}
                                                    </Badge>
                                                </TableCell>

                                                <TableCell>
                                                    {payment.payment_date
                                                        ? formatDateIndo(payment.payment_date)
                                                        : 'Belum Dibayar'}
                                                </TableCell>
                                                <TableCell>{formatDateIndo(payment.created_at)}</TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            )}
                        </CardContent>
                        <CardFooter>
                            <div className="flex w-full flex-col items-center justify-between gap-y-2 lg:flex-row">
                                <p className="text-sm text-muted-foreground">
                                    Menampilkan <span className="font-medium text-blue-600">{meta.to ?? 0}</span> dari{' '}
                                    {meta.total} Pembayaran
                                </p>
                                <div className="overflow-x-auto">
                                    {meta.has_pages && <PaginationTable meta={meta} links={links} />}
                                </div>
                            </div>
                        </CardFooter>
                    </Card>
                    ;{/* Filters */}
                </div>
            </div>
        </>
    );
}

Index.layout = (page) => <StudentLayout children={page} title={page.props.page_setting.title} />;
