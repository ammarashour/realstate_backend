
import { reactive,ref } from 'vue';

import PsApiService from '@templateCore/api/PsApiService';
import PsApi from '@templateCore/api/common/PsApi';
import PsResource from '@templateCore/api/common/PsResource';
import Blog from '@templateCore/object/Blog';
import BlogListParameterHolder from '@templateCore/object/holder/BlogListParameterHolder';
import { defineStore  } from 'pinia'

import makeSeparatedStore from '@templateCore/store/modules/core/PsSepetetedStore';


export const useBlogStoreState = makeSeparatedStore((key: string) =>
defineStore(`blogStore/${key}`,
 () => {

    const isNoMoreRecord = reactive({
        value: false
    })
    const blogList = reactive<PsResource<Blog[]>>(new PsResource());
    const blog = reactive<PsResource<Blog>>(new PsResource());
    const loading = reactive({
        value: false
    });
    let limit = ref(9);
    let offset: Number = 0;

    let paramHolder = reactive<BlogListParameterHolder>(new BlogListParameterHolder().BlogListParameterHolder());

    function hasData () {
        return blogList?.data != null && blogList!.data!.length > 0;
    }

    // function updateBlogList(responseData: PsResource<Blog[]>) {
    //     let data = responseData.data ? responseData.data : responseData;
    //     if (blogList != null
    //         && blogList.data != null
    //         && blogList.data.length > 0
    //         && offset != 0) {

    //         if (data != null && data.length > 0) {
    //             if(data?.length < limit.value){
    //                 isNoMoreRecord.value = true;
    //             } else {
    //                 isNoMoreRecord.value = false;
    //             }
    //             blogList.data.push(...data);
    //         }else {
    //             isNoMoreRecord.value = true;
    //         }

    //         blogList.code = responseData.code;
    //         blogList.status = responseData.status;
    //         blogList.message = responseData.message;

    //     } else {

    //         if(data?.length < limit.value || data == null){
    //             isNoMoreRecord.value = true;
    //         } else {
    //             isNoMoreRecord.value = false;
    //         }

    //         blogList.data = data;
    //         blogList.code = responseData.code;
    //         blogList.status = responseData.status;
    //         blogList.message = responseData.message;


    //     }

    //     if (blogList != null && blogList.data != null) {
    //         offset = blogList.data.length;
    //     }

    // }

    function updateBlogList(responseData: PsResource<Blog[]>) {

        if (blogList != null
            && blogList.data != null
            && blogList.data.length > 0
            && offset != 0) {

            if (responseData.data != null && responseData.data.length > 0) {
                if(responseData.data?.length < limit.value){
                    isNoMoreRecord.value = true;
                } else {
                    isNoMoreRecord.value = false;
                }
                blogList.data.push(...responseData.data);
            }else {
                isNoMoreRecord.value = true;
            }

            blogList.code = responseData.code;
            blogList.status = responseData.status;
            blogList.message = responseData.message;

        } else {

            if(responseData.data?.length < limit.value || responseData.data == null){
                isNoMoreRecord.value = true;
            } else {
                isNoMoreRecord.value = false;
            }

            blogList.data = responseData.data;
            blogList.code = responseData.code;
            blogList.status = responseData.status;
            blogList.message = responseData.message;


        }

        if (blogList != null && blogList.data != null) {
            offset = blogList.data.length;
        }

    }

    async function loadBlogList(loginUserId:string,holder: BlogListParameterHolder) {

        loading.value = true;

        const responseData = await PsApiService.getBlogList<Blog>(new Blog(), limit.value, offset, loginUserId, holder.toMap());

        updateBlogList(responseData);


        loading.value = false;

    }

    async function loadBlog(blogId: string, loginUserId: string) {

        loading.value = true;

        const responseData = await PsApiService.getBlogDetail<Blog>(new Blog(), blogId, loginUserId, limit.value, offset);

        blog.data = responseData.data;
        blog.code = responseData.code;
        blog.status = responseData.status;
        blog.message = responseData.message;

        loading.value = false;

        return blog;

    }

    async function resetBlogList(loginUserId:string,holder: BlogListParameterHolder) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApiService.getBlogList<Blog>(new Blog(), limit.value, offset, loginUserId, holder.toMap());

        updateBlogList(responseData);

        loading.value = false;

    }

    async function resetBlogListProps(blogs: PsResource<Blog[]>) {

        offset = 0;

        loading.value = true;

        const responseData = await PsApi.wrapDataFromProps(new Blog(), blogs);

        updateBlogList(responseData);


        loading.value = false;

    }

    async function loadBlogProps(blogObj: PsResource<Blog>) {

        loading.value = true;

        const responseData = await PsApi.wrapDataFromProps(new Blog(), blogObj);

        blog.data = responseData.data;
        blog.code = responseData.code;
        blog.status = responseData.status;
        blog.message = responseData.message;

        loading.value = false;

        return blog;
    }

    // async function decisionForDataCalling(loginUserId:string, holder: BlogListParameterHolder, blogs: PsResource<Blog[]>){
    //     if(await PsApi.checkIsEmpty(blogs)){
    //         await resetBlogListProps(blogs);
    //     } else {
    //         await resetBlogList(loginUserId, holder);
    //     }
    // }


    return {
        isNoMoreRecord,
        blogList,
        blog,
        loading,
        limit,
        offset,
        paramHolder,
        loadBlogList,
        loadBlog,
        resetBlogList,
        hasData,
        resetBlogListProps,
        // decisionForDataCalling,
        loadBlogProps
    }

}),
);
