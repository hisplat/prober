#! /bin/bash


cw=$(cd `dirname $0`; pwd)


function parse()
{
    iid=$1
    tarball=$2
    if [ -z "$tarball" ]; then
        echo "parse.sh TARBALL"
        exit 1
    fi

    tmpdir=`mktemp -d`

    tar xf "$tarball" -C "$tmpdir"

    product=`cat $tmpdir/bug/properties | awk '/ro.product.hisense.model/{print $2}' | sed -e 's/^\[//g' | sed -e 's/]$//g'`
    version=`cat $tmpdir/bug/properties | awk '/ro.build.hisense.softversion/{print $2}' | sed -e 's/^\[//g' | sed -e 's/]$//g'`
    builddate=`cat $tmpdir/bug/properties | awk '/ro.build.date.utc/{print $2}' | sed -e 's/^\[//g' | sed -e 's/]$//g'`
    device=`cat $tmpdir/bug/properties | awk '/ro.product.device/{print $2}' | sed -e 's/^\[//g' | sed -e 's/]$//g'`
    fingerprint=`cat $tmpdir/bug/properties | awk '/ro.build.fingerprint/{print $2}' | sed -e 's/^\[//g' | sed -e 's/]$//g'`

    echo "id = $iid"
    echo "product = $product"
    echo "version = $version"
    echo "builddate = $builddate"
    echo "device = $device"
    echo "fingerprint = $fingerprint"

    rm -fr "$tmpdir"
    
    local url="http://leopard.hismarttv.com/hisplat/prober/?action=api.v1.info.updateparsed&id=$iid&product=$product&version=$version&builddate=$builddate&device=$device&fingerprint=$fingerprint"
    echo "$url"
    ret=`curl $url`
    echo $ret
}

function main()
{
    uploadpath="$cw/../../upload"

    list=`curl http://leopard.hismarttv.com/hisplat/prober/?action=api.v1.info.unparsed`
    len=`echo $list | jq 'length'`

    if [ -z "$len" ]; then
        echo "all done."
        exit 1
    fi

    len=`expr $len - 1`


    for i in `seq 0 $len`; do
        iid=`echo $list | jq ".[$i].iid" | sed -e 's/"//g'`
        filename=`echo $list | jq ".[$i].filename" | sed -e 's/"//g'`
        filepath=$uploadpath/$filename
        parse "$iid" "$filepath"
    done
}

main "$@"



